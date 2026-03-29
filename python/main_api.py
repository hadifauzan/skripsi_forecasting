"""
FastAPI Application untuk Buffer Stock Calculation.
API ini dirancang lebih tahan terhadap error startup agar penyebab 503 bisa ditelusuri.
"""

from __future__ import annotations

import logging
import os
import re
import tempfile
from contextlib import asynccontextmanager
from typing import List, Optional

from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel

from buffer_stock_calc_refactored import BufferStockCalculator

logging.basicConfig(
    level=os.getenv("LOG_LEVEL", "INFO"),
    format="%(asctime)s | %(levelname)s | %(name)s | %(message)s",
)
logger = logging.getLogger(__name__)

calculator: Optional[BufferStockCalculator] = None
init_error: Optional[str] = None


class ProductBufferStock(BaseModel):
    product_name: str
    avg_daily_sales: float
    max_daily_sales: float
    buffer_stock: float
    safety_stock: float
    std_dev: float
    min_daily_sales: float
    max_daily_sales_actual: float
    median_daily_sales: float


class BufferStockSummary(BaseModel):
    total_products: int
    total_buffer_stock: float
    avg_buffer_stock: float
    total_safety_stock: float
    max_buffer_stock: float
    min_buffer_stock: float
    avg_lead_time: float
    max_lead_time: float
    calculation_formula: str


class HealthResponse(BaseModel):
    status: str
    service: str
    data_loaded: bool
    init_error: Optional[str] = None
    total_products: Optional[int] = None


def resolve_excel_path() -> str:
    env_path = os.getenv("EXCEL_PATH")
    if env_path:
        return env_path

    current_dir = os.path.dirname(os.path.abspath(__file__))
    return os.path.join(current_dir, "Dataset_Forecasting_ARIMA_Lengkap.xlsx")


@asynccontextmanager
async def lifespan(app: FastAPI):
    global calculator, init_error

    excel_path = resolve_excel_path()
    logger.info("Initializing calculator with dataset: %s", excel_path)

    try:
        calculator = BufferStockCalculator(
            excel_path=excel_path,
            avg_lead_time=5.4,
            max_lead_time=7,
        )
        init_error = None
        logger.info("Buffer Stock Calculator initialized successfully")
    except Exception as exc:
        calculator = None
        init_error = str(exc)
        logger.exception("Failed to initialize calculator")

    yield


app = FastAPI(
    title="Buffer Stock Calculator API",
    description="API untuk menghitung dan mengelola buffer stock produk",
    version="2.0.0",
    lifespan=lifespan,
)

allowed_origins_env = os.getenv("ALLOWED_ORIGINS", "http://localhost:3000,http://127.0.0.1:3000,http://localhost:8000,http://127.0.0.1:8000")
allowed_origins = [origin.strip() for origin in allowed_origins_env.split(",") if origin.strip()]

app.add_middleware(
    CORSMiddleware,
    allow_origins=allowed_origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


def get_calculator_or_raise() -> BufferStockCalculator:
    if calculator is None:
        raise HTTPException(
            status_code=503,
            detail=f"Calculator not initialized. Reason: {init_error or 'unknown error'}",
        )
    return calculator


def sanitize_filename(filename: str) -> str:
    sanitized = re.sub(r"[^A-Za-z0-9._-]", "_", filename).strip("._")
    return sanitized or "buffer_stocks_export.csv"


@app.get("/", tags=["Info"])
async def root():
    return {
        "service": "Buffer Stock Calculator API",
        "version": "2.0.0",
        "status": "running",
        "dataset_path": resolve_excel_path(),
        "endpoints": {
            "summary": "/api/buffer-stocks/summary",
            "all_products": "/api/buffer-stocks/all",
            "by_product": "/api/buffer-stocks/by-product?product_name=<name>",
            "top_products": "/api/buffer-stocks/top?n=10",
            "search": "/api/buffer-stocks/search?query=<keyword>",
            "products_list": "/api/buffer-stocks/products-list",
            "health": "/api/health",
            "debug_config": "/api/debug/config",
        },
    }


@app.get("/api/health", response_model=HealthResponse, tags=["Health"])
async def health_check():
    if calculator is None:
        return HealthResponse(
            status="unhealthy",
            service="Buffer Stock Calculator",
            data_loaded=False,
            init_error=init_error,
        )

    return HealthResponse(
        status="healthy",
        service="Buffer Stock Calculator",
        data_loaded=True,
        total_products=len(calculator.get_all_buffer_stocks()),
    )


@app.get("/api/debug/config", tags=["Debug"])
async def debug_config():
    excel_path = resolve_excel_path()
    return {
        "excel_path": excel_path,
        "exists": os.path.exists(excel_path),
        "initialized": calculator is not None,
        "init_error": init_error,
        "allowed_origins": allowed_origins,
    }


@app.get("/api/buffer-stocks/summary", response_model=BufferStockSummary, tags=["Buffer Stock"])
async def get_summary():
    calc = get_calculator_or_raise()
    return calc.get_summary()


@app.get("/api/buffer-stocks/all", response_model=List[ProductBufferStock], tags=["Buffer Stock"])
async def get_all_buffer_stocks(
    sort_by: str = Query("buffer_stock", description="Field untuk sorting"),
    order: str = Query("desc", description="Urutan: asc atau desc"),
):
    calc = get_calculator_or_raise()
    data = calc.get_all_buffer_stocks()

    reverse = order.lower() == "desc"
    try:
        data = sorted(data, key=lambda x: x[sort_by], reverse=reverse)
    except KeyError as exc:
        raise HTTPException(status_code=400, detail=f"Invalid sort field: {sort_by}") from exc

    return data


@app.get("/api/buffer-stocks/by-product", response_model=ProductBufferStock, tags=["Buffer Stock"])
async def get_by_product(product_name: str = Query(..., description="Nama produk")):
    calc = get_calculator_or_raise()
    data = calc.get_buffer_stock_by_product(product_name)
    if data is None:
        raise HTTPException(status_code=404, detail=f"Product '{product_name}' not found")
    return data


@app.get("/api/buffer-stocks/top", response_model=List[ProductBufferStock], tags=["Buffer Stock"])
async def get_top_products(n: int = Query(10, description="Jumlah top produk", ge=1, le=100)):
    calc = get_calculator_or_raise()
    return calc.get_top_products(n)


@app.get("/api/buffer-stocks/search", response_model=List[ProductBufferStock], tags=["Buffer Stock"])
async def search_products(
    query: str = Query(..., description="Search query (partial product name)"),
    limit: int = Query(20, description="Jumlah hasil maksimal", ge=1, le=100),
):
    calc = get_calculator_or_raise()
    query_lower = query.lower()
    results = [
        item for item in calc.get_all_buffer_stocks()
        if query_lower in item["product_name"].lower()
    ][:limit]

    if not results:
        raise HTTPException(status_code=404, detail=f"No products matching '{query}'")
    return results


@app.get("/api/buffer-stocks/products-list", tags=["Buffer Stock"])
async def get_products_list():
    calc = get_calculator_or_raise()
    products = [item["product_name"] for item in calc.get_all_buffer_stocks()]
    return {"total": len(products), "products": sorted(products)}


@app.post("/api/buffer-stocks/export", tags=["Buffer Stock"])
async def export_csv(output_filename: str = Query("buffer_stocks_export.csv")):
    calc = get_calculator_or_raise()
    try:
        safe_name = sanitize_filename(output_filename)
        output_path = os.path.join(tempfile.gettempdir(), safe_name)
        calc.export_to_csv(output_path)
        return {
            "status": "success",
            "message": f"Data exported to {safe_name}",
            "file_path": output_path,
        }
    except Exception as exc:
        logger.exception("Export failed")
        raise HTTPException(status_code=500, detail=f"Export failed: {exc}") from exc


if __name__ == "__main__":
    import uvicorn

    print("Starting Buffer Stock Calculator API...")
    print("API Docs: http://localhost:1337/docs")
    print("ReDoc: http://localhost:1337/redoc")

    uvicorn.run(
        "main_api:app",
        host="0.0.0.0",
        port=1337,
        reload=True,
        log_level="info",
    )
