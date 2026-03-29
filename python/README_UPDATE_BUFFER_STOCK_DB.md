# Buffer Stock Database Update Script

Script ini menghitung buffer stock berdasarkan data Excel dan secara otomatis mengupdate tabel `master_items_stock` di database.

## Cara Kerja

1. **Load data penjualan** dari Excel file
2. **Hitung buffer stock** untuk setiap produk menggunakan formula: 
   - `Buffer Stock = (Max Daily Sales × Max Lead Time) – (Avg Daily Sales × Avg Lead Time)`
3. **Match produk** berdasarkan nama dengan item di database
4. **Update database** tabel `master_items_stock` dengan nilai buffer stock yang telah dihitung

## Setup & Konfigurasi

### 1. Copy .env Configuration
```bash
copy .env.example .env
```

### 2. Edit file `.env` dengan database credentials:
```
DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=your_password
DB_DATABASE=skripsi_forecasting
```

### 3. Install dependencies (jika belum)
```bash
pip install -r requirements.txt
```

Package yang diperlukan:
- `pymysql==1.1.0` - Koneksi ke database MySQL
- `python-dotenv==1.0.0` - Load environment variables
- `pandas` - Data processing
- `numpy` - Numerical calculations
- `openpyxl` - Read Excel files

## Penggunaan

### Run dari Command Line

```bash
# Dari direktori python/
python update_buffer_stock_db.py
```

### Run dengan Parameter (Optional)

Jika ingin menggunakan file Excel yang berbeda:

```python
from update_buffer_stock_db import BufferStockDatabaseUpdater

updater = BufferStockDatabaseUpdater(
    excel_path='path/to/your/data.xlsx',
    avg_lead_time=5.4,
    max_lead_time=7
)

result = updater.update_buffer_stocks(inventory_id=1)
print(result)
```

### Output Contoh

```
================================================================================
UPDATE SUMMARY
================================================================================
Total items processed: 45
Successfully updated: 43
Items not found in database: 2
Errors occurred: 0
================================================================================
✅ Buffer stock update BERHASIL!
   Updated: 43 items
   Not found: 2 items
   Errors: 0 items
```

## Struktur Database

Script akan mengupdate tabel `master_items_stock`:

```sql
CREATE TABLE master_items_stock (
    item_stock_id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    inventory_id INT NOT NULL,
    stock INT DEFAULT 0,
    buffer_stock INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Columns:
- `item_stock_id` - Primary key
- `item_id` - Reference ke `master_items` table
- `inventory_id` - Reference ke inventory (default: 1)
- `stock` - Current stock level
- `buffer_stock` - **DIUPDATE OLEH SCRIPT INI**
- `created_at` - Timestamp pembuatan record
- `updated_at` - Timestamp update terakhir

## Troubleshooting

### Error: "Database connection error"
- Pastikan MySQL server running
- Verify DB_HOST, DB_USERNAME, DB_PASSWORD di file `.env`
- Check kalau database `skripsi_forecasting` sudah exist

### Error: "Product not found in database"
- Berarti ada produk di Excel yang tidak cocok dengan nama item di database
- Review nama produk di file Excel vs database
- Bisa di-skip dengan aman, buffer stock untuk produk tersebut tidak akan diupdate

### Error: "No such file or directory"
- Pastikan path ke Excel file benar
- Default path: `Dataset_Forecasting_ARIMA_Lengkap.xlsx` (di direktori yang sama dengan script)

## Advanced Usage

### Integration dengan FastAPI

Bisa di-trigger dari API endpoint:

```python
from fastapi import APIRouter
from update_buffer_stock_db import BufferStockDatabaseUpdater

router = APIRouter()

@router.post("/api/buffer-stocks/update-database")
async def update_buffer_stocks_endpoint():
    try:
        updater = BufferStockDatabaseUpdater(
            excel_path='path/to/data.xlsx'
        )
        result = updater.update_buffer_stocks(inventory_id=1)
        return {"status": "success", **result}
    except Exception as e:
        return {"status": "error", "error": str(e)}
```

### Scheduled Update (Cron Job)

Untuk update otomatis setiap hari:

```bash
# Edit crontab
crontab -e

# Add this line (jalankan setiap hari jam 2 pagi)
0 2 * * * /usr/bin/python3 /path/to/update_buffer_stock_db.py
```

## Logging

Script sudah include logging. Untuk melihat log lebih detail, check console output saat menjalankan script.

Format log:
```
2024-01-15 10:30:45 | INFO | __main__ | ✓ Connected to database: skripsi_forecasting
2024-01-15 10:30:45 | INFO | __main__ | ✓ Loaded 50 items from database
2024-01-15 10:30:45 | INFO | __main__ | ✓ Updated Product A: buffer_stock = 125
...
```

## Files Related

- `buffer_stock_calc.py` - Core calculation module
- `main_api.py` - FastAPI endpoints
- `.env` - Database configuration (create dari .env.example)
- `Dataset_Forecasting_ARIMA_Lengkap.xlsx` - Data source

## Author Notes

- Lead time values (avg: 5.4, max: 7) bisa di-adjust sesuai kondisi bisnis
- Script menggunakan percentil 95% untuk max_daily_sales (bukan hardmax)
- Safety stock dihitung menggunakan z-score 1.65 (95% service level)
- Semua nilai buffer_stock di-round ke integer saat disimpan ke database
