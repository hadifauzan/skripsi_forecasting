# Buffer Stock System - Architecture Overview

## Complete System Flow

```
Excel Data
│
├─→ API (FastAPI - port 1337)
│   ├─→ Endpoint: /api/buffer-stocks/all
│   ├─→ Endpoint: /api/buffer-stocks/by-product
│   └─→ Endpoint: /api/buffer-stocks/summary
│
├─→ Web Interface (Laravel)
│   └─→ raw_materials.blade.php
│       ├─→ Display buffer stock from API
│       ├─→ Display buffer stock from database
│       └─→ Detail modal for analysis
│
└─→ Database Update (Python Script)
    └─→ update_buffer_stock_db.py
        ├─→ Read Excel
        ├─→ Calculate buffer stock
        └─→ Update master_items_stock table
```

## Components & Their Purpose

### 1. Data Source
**File**: `Dataset_Forecasting_ARIMA_Lengkap.xlsx`
- Contains daily sales data per product
- Used by both API and database update script
- Columns: Date, Product_A, Product_B, ... Product_N, Total_Sales

### 2. Calculation Module
**File**: `python/buffer_stock_calc.py`
- Class: `BufferStockCalculator`
- Reads Excel data
- Calculates statistics per product:
  - avg_daily_sales (average)
  - max_daily_sales (percentile 95%)
  - std_dev (standard deviation)
  - buffer_stock (calculated value)
  - safety_stock (statistical safety stock)
- Methods:
  - `get_all_buffer_stocks()` - Returns all products stats
  - `get_buffer_stock_by_product(name)` - Returns specific product
  - `export_to_csv()` - Export results

### 3. FastAPI Backend
**File**: `python/main_api.py`
- Framework: FastAPI
- Port: 1337 (localhost:1337)
- CORS: Enabled for localhost:8000, 127.0.0.1:8000, localhost:1337
- Endpoints:
  - `/api/health` - Health check
  - `/api/buffer-stocks/all` - All products
  - `/api/buffer-stocks/by-product?name=X` - Specific product
  - `/api/buffer-stocks/summary` - Aggregate stats
  - `/api/buffer-stocks/top?n=10` - Top N products
  - And more...
- Uses: BufferStockCalculator from buffer_stock_calc.py

### 4. Laravel Web Application
**File**: `resources/views/admin_inventory/raw_materials.blade.php`
- Admin panel for inventory management
- Database-driven inventory data
- Displays columns: Product, Current Stock, Buffer Stock (from DB)
- JavaScript enhancement:
  - Fetches from API on page load
  - Updates table with calculated buffer stock values
  - Shows tooltips with statistics
  - Opens detail modal for full analysis
- Component: `resources/views/components/buffer_stock_info.blade.php`
  - Reusable component for API calls
  - Handles errors gracefully
  - Caches results to reduce API calls

### 5. Database - MySQL
**Table**: `master_items_stock`
```sql
CREATE TABLE master_items_stock (
    item_stock_id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    inventory_id INT NOT NULL,
    stock INT DEFAULT 0,
    buffer_stock INT DEFAULT 0,        ← UPDATED BY SCRIPT
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 6. Database Update Script
**File**: `python/update_buffer_stock_db.py`
- Class: `BufferStockDatabaseUpdater`
- Purpose: Calculate buffer stock from Excel and update database
- Process:
  1. Load configuration from .env file
  2. Initialize BufferStockCalculator with Excel data
  3. Connect to MySQL database
  4. Get mapping of product names → item_ids from master_items table
  5. For each calculated buffer stock:
     - Match product name to item_id
     - UPDATE master_items_stock SET buffer_stock = calculated_value
     - Or INSERT if record doesn't exist
  6. Commit changes
  7. Return statistics
- Configuration: .env file with DB credentials
- Error handling: Logs failures, continues processing
- Output: Statistics summary

### 7. Launcher Scripts
- **run_update.bat** - Windows batch file launcher
- **run_update.ps1** - Windows PowerShell launcher
- Both check for .env file and Python installation

## Data Flow Diagram

```
CALCULATION PROCESS:
─────────────────────

Excel Data (Daily Sales)
    ↓
BufferStockCalculator
    ├─ Load data
    ├─ Group by product
    ├─ Calculate statistics
    │  ├─ avg_daily_sales = mean()
    │  ├─ max_daily_sales = percentile(95%)
    │  ├─ std_dev = std()
    │  └─ buffer_stock = (max * max_lead) - (avg * avg_lead)
    └─ Return results


DUAL PATH ARCHITECTURE:
──────────────────────

Path A: Real-time API
Excel → BufferStockCalculator → FastAPI → Browser → UI Display

Path B: Persistent Database
Excel → BufferStockCalculator → Database Update Script → MySQL → UI Display


API CALL FLOW:
──────────────

Browser (localhost:8000)
    ↓ CORS Request
Admin Panel JavaScript
    ↓ Fetch /api/buffer-stocks/all
API Server (localhost:1337)
    ↓ Load from cache / recalculate
BufferStockCalculator
    ↓ Process Excel
API Response
    ↓ JSONify results
Browser JavaScript
    ├─ Cache result
    ├─ Update DOM
    ├─ Show tooltips
    └─ Enable detail modal


DATABASE UPDATE FLOW:
────────────────────

User runs: python update_buffer_stock_db.py
    ↓
Load .env config
    ↓
Initialize Calculator with Excel path
    ↓
Connect to MySQL
    ↓
Get product → item_id mapping
    ↓
For each product:
    ├─ Calculate buffer_stock
    ├─ Find item_id from name
    └─ UPDATE/INSERT master_items_stock
    ↓
Commit transaction
    ↓
Log statistics & completion
```

## Configuration Files

### .env (Database Configuration)
```
DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=password
DB_DATABASE=skripsi_forecasting
EXCEL_PATH=Dataset_Forecasting_ARIMA_Lengkap.xlsx (optional)
```

### Python requirements.txt
- pandas - Data manipulation
- numpy - Numerical operations
- openpyxl - Excel reading
- FastAPI - REST API framework
- uvicorn - ASGI server
- python-dotenv - Configuration loader
- pymysql - MySQL connector
- Guzzle... (Laravel side)

## Lead Time Parameters

Default values in `BufferStockCalculator`:
- **avg_lead_time**: 5.4 days (average time from order to delivery)
- **max_lead_time**: 7 days (maximum time from order to delivery)

These affect buffer stock calculation:
```
buffer_stock = (max_daily_sales × max_lead_time) - (avg_daily_sales × avg_lead_time)
```

Can be adjusted in:
1. API startup: `main_api.py` initialization
2. Database update: `.env` file or script parameters
3. Jupyter notebook: Cell parameters

## Workflow Scenarios

### Scenario 1: First Time Setup
1. ✅ Ensure Excel file exists with sales data
2. ✅ Copy .env.example to .env
3. ✅ Configure database credentials in .env
4. ✅ Run update_buffer_stock_db.py
5. ✅ Database now has buffer_stock values
6. ✅ Start API: python main_api.py
7. ✅ Open Laravel admin panel
8. ✅ See buffer stock values from both DB and API

### Scenario 2: Update When Excel Changes
1. ✅ Update Excel file with new sales data
2. ✅ Run update_buffer_stock_db.py again
3. ✅ Database buffer_stock values are updated
4. ✅ API automatically uses new calculations
5. ✅ Laravel UI shows new buffer_stock values

### Scenario 3: Scheduled Daily Update
1. ✅ Create Windows Task Scheduler job
2. ✅ Schedule: python update_buffer_stock_db.py (daily at 2 AM)
3. ✅ Automatic daily calculation and database update

### Scenario 4: API-Only (Without Database)
1. ✅ Start API server: python main_api.py
2. ✅ API serves calculated buffer stock
3. ✅ UI displays from API cache
4. ✅ No database persistence needed

## Performance Considerations

### API Performance
- First call: ~2-5 seconds (loads and caches Excel)
- Subsequent calls: ~50-100ms (cached data)
- Browser caches: ~100KB data
- No repeated API calls per session

### Database Update Performance
- Process: ~5-10 seconds for 50-100 products
- Bottleneck: Excel loading and matrix operations
- Database writes are fast (batch concept)

### Optimization Tips
1. Cache API responses on Laravel side
2. Run database update during off-peak hours
3. Consider data warehouse for historical analysis
4. Use percentile 95% instead of max (more stable)

## Troubleshooting Guide

### API Not Starting
- Check Python installation
- Verify port 1337 is available
- Check Excel file path in main_api.py
- Review error logs

### Database Update Fails
- Verify .env file exists and correct
- Check MySQL is running
- Verify database credentials
- Check Excel file path
- Look for "Product not found" errors (expected)

### Products Not Found
- Product name in Excel must match master_items.name_item
- Case-sensitive matching
- Trim whitespace in product names
- Review logs for exact name mismatches

### API vs Database Values Don't Match
- Check if database update has been run
- API uses fresh calculation, DB uses stored value
- Run update_buffer_stock_db.py to sync database

## Files Summary

| File | Purpose | Type |
|------|---------|------|
| buffer_stock_calc.py | Core calculations | Python Module |
| main_api.py | REST API server | Python FastAPI |
| update_buffer_stock_db.py | Database update | Python Script |
| .env.example | Config template | Text Config |
| run_update.bat | Windows launcher | Batch Script |
| run_update.ps1 | PowerShell launcher | PowerShell |
| raw_materials.blade.php | Admin UI | HTML/Blade |
| buffer_stock_info.blade.php | Reusable component | HTML/Blade |
| requirements.txt | Dependencies | Python Config |
| Dataset_Forecasting_ARIMA_Lengkap.xlsx | Sales data | Excel Data |
| master_items_stock (table) | Persistent storage | MySQL Table |

## Next Steps

1. **Immediate**: Run update_buffer_stock_db.py to populate database
2. **Monitor**: Check if buffer stock values are reasonable
3. **Validate**: Verify values match business expectations
4. **Schedule**: Set up recurring update (daily/weekly)
5. **Analyze**: Review top products with highest buffer stock
6. **Optimize**: Adjust lead times if needed based on actual performance

## Support Resources

- `README_UPDATE_BUFFER_STOCK_DB.md` - Detailed documentation
- `QUICK_START_DB_UPDATE.txt` - Quick setup guide
- `README_BUFFER_STOCK_API.md` - API documentation
- Inline code comments in all Python files
