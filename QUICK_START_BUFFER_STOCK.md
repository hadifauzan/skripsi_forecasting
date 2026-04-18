# 🚀 Quick Start - Buffer Stock System

Panduan cepat untuk mulai menggunakan Buffer Stock, Forecasting & Stock Opname system.

## ⚡ 3 Langkah Setup

### 1️⃣ Run Migration
```bash
cd c:\laragon\www\skripsi_forecasting
php artisan migrate
```

### 2️⃣ Seed Dummy Data
```bash
php artisan db:seed --class=BufferStockDataSeeder
```

### 3️⃣ Akses Dashboard
Buka browser, login dengan role `owner` atau `admin_inventory`, lalu navigasi ke:
- **Dashboard**: `/admin/inventory-dashboard`
- **Buffer Stock**: `/admin/inventory/buffer-stock/raw-materials`
- **Forecasting**: `/admin/inventory/forecasting/demand`
- **Stock Opname**: `/admin/inventory/stock-opname`
- **Production**: `/admin/inventory/production-overview`

---

## 📊 File-File Kunci

### Models (Location: `app/Models/`)
```
MasterItemRawMaterial.php          ← Bahan baku
MasterItemBillOfMaterials.php      ← Resep produksi
ProductionOrder.php                ← Pesanan produksi
RawMaterialIn.php                  ← Penerimaan bahan
RawMaterialOut.php                 ← Pemakaian bahan
FinishedGoodsIn.php                ← Produksi selesai
FinishedGoodsOut.php               ← Penjualan
BufferStockConfig.php              ← Konfigurasi
StockAdjustment.php                ← Opname & adjustment
```

### Service (Location: `app/Services/`)
```
BufferStockCalculationService.php  ← Logika perhitungan
```

### Controller (Location: `app/Http/Controllers/Admin/`)
```
InventoryDashboardController.php   ← Semua endpoints
```

### Views (Location: `resources/views/admin_inventory/`)
```
buffer_stock_raw_materials.blade.php       ← Buffer stock view
demand_forecasting.blade.php               ← Forecasting view
stock_opname.blade.php                     ← Opname view
production_overview.blade.php              ← Production view
```

### Seeder (Location: `database/seeders/`)
```
BufferStockDataSeeder.php          ← Dummy data generator
```

---

## 🎯 Fitur Utama

### 1. Buffer Stock Calculation
**Apa?** Otomatis hitung stok minimum untuk setiap bahan berdasarkan:
- Permintaan rata-rata harian
- Lead time supplier
- Variabilitas permintaan
- Target service level

**Akses**: Sidebar → Buffer Stock → Click "Detail" untuk breakdown

**Formula**:
```
Buffer Stock = (Avg Daily Usage × Safety Days) + Safety Stock
Reorder Point = (Avg Daily Usage × Lead Time) + Safety Stock
```

### 2. Demand Forecasting
**Apa?** Proyeksi permintaan produk 30 hari ke depan based on 90 hari history

**Fitur**:
- Rata-rata daily demand
- Confidence interval (95%)
- Forecast total untuk perencanaan produksi
- Variance untuk risk assessment

### 3. Stock Opname Tracking
**Apa?** Track semua penyesuaian stok dengan detail

**Riwayat**:
- Tanggal adjustment
- Qty yang disesuaikan
- Alasan (damaged, lost, correction dll)
- Siapa yang adjust
- Breakdown by type dan reason

### 4. Production Overview
**Apa?** Pantau seluruh alur produksi:
- Raw material in/out
- Production orders
- Finished goods in/out
- Production flow diagram

---

## 💡 Usage Examples

### Scenario 1: Bahan Baku Hampir Habis
1. Buka "Buffer Stock" → Lihat yang statusnya CRITICAL atau LOW
2. Click "Detail" untuk lihat kapan harus pesan
3. Lihat "Items to Order" untuk list lengkap & qty yang direkomendasikan
4. Buat PO ke supplier sesuai rekomendasi

### Scenario 2: Planning Produksi Minggu Depan
1. Buka "Forecasting" → Lihat proyeksi demand per produk
2. Cross-check dengan current stock di buffer stock
3. Tentukan qty produksi = forecast - current stock + safety buffer
4. Input ke "Production Orders"

### Scenario 3: Stock Opname Bulanan
1. Buka "Stock Opname" → Lihat history adjustments
2. Lihat breakdown by material untuk material yang sering adjusted
3. Identify pattern (e.g. selalu shortage di material A)
4. Update "BufferStockConfig" jika diperlukan

### Scenario 4: Monitor Produksi Harian
1. Buka "Production Overview"
2. Check "Production Orders" tab untuk status
3. Monitor "Raw Material Flow" untuk availability
4. Check "Finished Goods" untuk output

---

## 🔧 Customization

### Ubah Buffer Stock Parameters
Edit `BufferStockConfig` tabel atau via admin panel:

```php
// Untuk raw material dengan demand variabilitastinggi
[
    'safety_days' => 5,              // Lebih banyak safety stock
    'demand_variability_factor' => 1.96  // Z-score untuk 97.5%
]
```

### Ubah Lookback Period Untuk Forecast
Di view `demand_forecasting.blade.php`, ubah:
```blade
$forecastDays = (int) $request->get('forecast_days', 30);
```

### Tambah Custom Calculation
Edit `BufferStockCalculationService.php` untuk:
- Tambah logic seasonal adjustment
- Custom safety stock formula
- Integration dengan supplier data

---

## 📈 Interpretation Guide

### Buffer Stock Status
| Status | Condition | Action |
|--------|-----------|--------|
| 🔴 CRITICAL | Stock < Buffer | Order ASAP |
| 🟡 LOW | Stock < Reorder Pt | Prepare PO |
| 🟢 NORMAL | Stock ≥ Reorder Pt | No action |
| 🔵 OVERSTOCK | Stock > Max | Hold ordering |

### Confidence Interval
- **Lower Bound**: Worst-case demand (safety stock level)
- **Upper Bound**: Best-case demand (capacity planning)
- **Range**: Between bounds = normal fluctuation

---

## 🐛 Common Issues & Solutions

**Q: Data tidak muncul?**  
A: 
```bash
php artisan migrate --fresh  # WARNING: Drop all tables first!
php artisan db:seed --class=BufferStockDataSeeder
```

**Q: Angka buffer stock berubah setiap hari?**  
A: Normal! Sistem recalculate berdasarkan data terakhir. Sync to DB saat sudah stabil.

**Q: Forecasting angka tinggi/rendah?**  
A: Check data historis (min 30 hari diperlukan). Review seasonal factors.

**Q: Gimana kalau supplier lead time berubah?**  
A: Update `lead_time_days` di `MasterItemRawMaterial` atau `BufferStockConfig`

---

## � Buffer Stock Database Setup (NEW)

Untuk mengimport data buffer stock hasil Python calculation langsung ke database:

### Setup Steps
```bash
# Step 1: Generate CSV dari Python
cd python
python buffer_stock_calculation.py
# File akan tersimpan: python/buffer_stock_per_produk.csv

# Step 2: Run Migration (dari root folder)
cd ..
php artisan migrate

# Step 3: Run Seeder
php artisan db:seed --class=BufferStockSeeder
```

### Files Created
- `database/migrations/2026_04_16_000001_create_buffer_stock_table.php` - Tabel schema
- `database/seeders/BufferStockSeeder.php` - CSV importer
- `BUFFER_STOCK_DATABASE_SETUP.md` - Dokumentasi lengkap

### CSV Format yang Diharapkan
```
Produk,Max_Daily_Sales,Avg_Daily_Sales,Standar_Deviasi,Buffer_Stock_Unit,Safety_Stock_95percent_Unit,ROP_Unit,Avg_Lead_Time_Hari,Max_Lead_Time_Hari,Rumus_Buffer_Stock,Rumus_ROP

Gentle Baby,150.50,75.25,35.10,245.80,92.33,498.20,5.4,7.0,"(Max Daily Sales x 7) - (Avg Daily Sales x 5.4)","(Avg Daily Sales x 5.4) + Safety Stock"
```

### Query Data
```php
// Di controller atau tinker
$bufferStocks = DB::table('buffer_stock')->get();
$topRop = DB::table('buffer_stock')->orderBy('rop_unit', 'desc')->limit(10)->get();
$stats = DB::table('buffer_stock')->select(
    DB::raw('COUNT(*) as total'),
    DB::raw('SUM(buffer_stock_unit) as total_buffer'),
    DB::raw('AVG(rop_unit) as avg_rop')
)->first();
```

### Upsert Logic
Seeder menggunakan upsert → safe dijalankan berkali-kali tanpa duplikasi

---

## 📞 Need Help?

1. Read: `BUFFER_STOCK_IMPLEMENTATION_GUIDE.md` (full documentation)
2. Read: `BUFFER_STOCK_DATABASE_SETUP.md` (database integration guide)
3. Check: `routes/web.php` untuk semua available routes
4. Inspect: Database schema di migration files
5. Debug: `php artisan tinker` untuk query manual

---

**Selamat menggunakan Buffer Stock System! 🎉**
