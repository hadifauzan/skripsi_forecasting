# Buffer Stock, Forecasting & Stock Opname System Implementation

## 🎯 Overview

Sistem komprehensif untuk menghitung dan mengelola Buffer Stock, melakukan Demand Forecasting, dan melacak Stock Opname. Sistem ini mengintegrasikan data dari berbagai tabel master dan transaksi untuk memberikan analisis yang akurat tentang manajemen persediaan.

## 📋 Komponen yang Diimplementasikan

### 1. **Models (Database)**
- `MasterItemRawMaterial` - Data bahan baku dengan kriteria penyimpanan
- `MasterItemBillOfMaterials` - Resep produksi (BOM)
- `ProductionOrder` - Pesanan produksi
- `RawMaterialIn` - Penerimaan bahan baku dari supplier
- `RawMaterialOut` - Penggunaan bahan baku dalam produksi
- `FinishedGoodsIn` - Penerimaan produk jadi dari produksi
- `FinishedGoodsOut` - Penjualan produk jadi ke pelanggan
- `BufferStockConfig` - Konfigurasi parameter buffer stock
- `StockAdjustment` - Riwayat penyesuaian stok (stock opname)

### 2. **Service Layer**
**File**: `app/Services/BufferStockCalculationService.php`

#### Method Utama:
- `calculateBufferStock($itemRawId, $lookbackDays = 90)` - Hitung buffer stock untuk material tertentu
- `calculateAllBufferStocks()` - Hitung semua buffer stocks
- `syncAllBufferStocks()` - Simpan hasil kalkulasi ke database
- `getForecastDemand($itemId, $forecastDays = 30)` - Proyeksi permintaan
- `getStockAdjustmentAnalysis($itemRawId, $daysBack = 30)` - Analisis perubahan stok

#### Rumus Perhitungan:
```
Safety Stock = Variability Factor × Usage Std Dev × √Lead Time
Reorder Point = (Avg Daily Usage × Lead Time) + Safety Stock
Buffer Stock = (Avg Daily Usage × Safety Days) + Safety Stock
Max Stock = Reorder Point + Economic Order Quantity
```

### 3. **Controller**
**File**: `app/Http/Controllers/Admin/InventoryDashboardController.php`

#### Routes:
1. **Buffer Stock Management**
   - `GET /inventory/buffer-stock/raw-materials` - List bahan dengan perhitungan buffer stock
   - `GET /inventory/buffer-stock/details/{itemRawId}` - Detail perhitungan untuk item tertentu
   - `GET /inventory/buffer-stock/items-to-order` - Item yang perlu dipesan segera
   - `POST /inventory/buffer-stock/sync` - Sinkronisasi ke database

2. **Demand Forecasting**
   - `GET /inventory/forecasting/demand` - Proyeksi permintaan semua produk

3. **Stock Opname**
   - `GET /inventory/stock-opname` - Riwayat adjustment dan opname

4. **Production Overview**
   - `GET /inventory/production-overview` - Pantau alur produksi lengkap

### 4. **Views** (Blade Templates)
1. `buffer_stock_raw_materials.blade.php` - Dashboard buffer stock
2. `demand_forecasting.blade.php` - Forecast demand dengan confidence interval
3. `stock_opname.blade.php` - Riwayat opname dan adjustment
4. `production_overview.blade.php` - Alur produksi lengkap

### 5. **Seeder Data**
**File**: `database/seeders/BufferStockDataSeeder.php`

Menghasilkan dummy data untuk:
- 5 bahan baku dengan karakteristik berbeda
- 2 produk jadi dengan resep produksi
- 90 hari riwayat penerimaan bahan
- 90 hari riwayat produksi
- 90 hari riwayat penjualan
- Stock adjustment history

## 🚀 Setup & Instalasi

### Langkah 1: Jalankan Migration
```bash
php artisan migrate
```

Ini akan membuat semua tabel yang diperlukan:
- `master_items_raw_material`
- `master_items_bill_of_materials`
- `production_orders`
- `raw_material_in`
- `raw_material_out`
- `finished_goods_in`
- `finished_goods_out`
- `buffer_stock_config`
- `stock_adjustment`

### Langkah 2: Jalankan Seeder

**Option A: Hanya Buffer Stock Seeder**
```bash
php artisan db:seed --class=BufferStockDataSeeder
```

**Option B: Semua Seeder termasuk yang lain**
```bash
php artisan db:seed
```

Untuk force seed (replace data):
```bash
php artisan db:seed --class=BufferStockDataSeeder --force
```

### Langkah 3: Verifikasi Data
```bash
php artisan tinker
# Cek data raw material
>>> App\Models\MasterItemRawMaterial::count()
=> 5
```

## 📊 Menggunakan Sistem

### 1. Buffer Stock Analysis
**URL**: `/admin/inventory/buffer-stock/raw-materials`

**Fitur**:
- View semua bahan dengan perhitungan buffer stock
- Lihat status stok (Critical, Low, Normal, Overstock)
- Click "Detail" untuk melihat breakdown perhitungan
- Sinkronisasi hasil perhitungan ke database
- Link ke "Items to Order" untuk kebutuhan pemesanan

### 2. Demand Forecasting
**URL**: `/admin/inventory/forecasting/demand`

**Output**:
- Rata-rata permintaan harian dari 90 hari terakhir
- Proyeksi permintaan untuk periode tertentu (default 30 hari)
- Confidence interval (95%) - range optimis dan pesimis
- Nilai HPP dan harga jual untuk analisis margin

### 3. Stock Opname
**URL**: `/admin/inventory/stock-opname`

**Tabs**:
1. **Adjustment History** - Semua perubahan stok dengan detail
2. **Materials Adjusted** - Ringkasan per material
3. **Summary** - Breakdown by type dan reason

### 4. Production Overview
**URL**: `/admin/inventory/production-overview`

**Sections**:
1. Production Orders - Pesanan yang sedang/telah diproduksi
2. Raw Material Flow - Masuk/keluar bahan baku
3. Finished Goods - Produksi dan penjualan
4. Production Flow Diagram - Visualisasi proses

## 🔧 Konfigurasi

### Buffer Stock Config
Ubah parameter di menu `BufferStockConfig`:

```php
// Default config untuk raw material
[
    'material_type' => 'raw_material',
    'safety_days' => 3,                    // Hari buffer
    'lead_time_days' => 7,                  // Lead time supplier
    'demand_variability_factor' => 1.65,   // Z-score untuk 95% service level
    'service_level_percentage' => 95.0,    // Target service level
    'min_reorder_quantity' => 50            // Minimum order
]
```

## 📈 Interpretasi Hasil

### Stock Status Colors
- 🔴 **CRITICAL** (Merah) - Stok < Buffer Stock → Pesan SEGERA
- 🟡 **LOW** (Kuning) - Stok < Reorder Point → Siapkan PO
- 🟢 **NORMAL** (Hijau) - Stok normal dan aman
- 🔵 **OVERSTOCK** (Biru) - Stok melebihi max → Tahan pemesanan

### Rekomendasi Column
Sistem otomatis memberikan rekomendasi berdasarkan status:
- "Order immediately - stock critical"
- "Order soon - stock below reorder point"
- "Stock sufficient - no action needed"

## 📊 API Response Samples

### Get Buffer Stock Detail
```json
{
  "material": {
    "item_raw_id": 1,
    "material_name": "Sugar (Gula)",
    "unit": "kg",
    "current_stock": 500,
    "purchase_price": 12000
  },
  "calculation": {
    "avg_daily_usage": 15.5,
    "buffer_stock": 235.45,
    "reorder_point": 345.67,
    "max_stock": 745.67,
    "safety_stock": 125.45,
    "recommendation": "Stock sufficient - no action needed"
  },
  "usage_history": [...],
  "receipt_history": [...]
}
```

## 🔄 Workflow Operasional

### Daily Operations
1. **Morning Check** - Lihat `items-to-order` untuk kebutuhan segera
2. **Production Planning** - Lihat `buffer-stock` untuk stock availability
3. **Monitoring** - Check `production-overview` untuk progress

### Weekly Review
1. Analisis `demand-forecasting` untuk planning minggu depan
2. Review `stock-opname` untuk discrepancies
3. Sinkronisasi buffer stock jika ada perubahan trend

### Monthly Review
1. Update parameter di `BufferStockConfig` berdasarkan trend
2. Analisis penuh dalam `production-overview`
3. Identifikasi improvement opportunities

## 🐛 Troubleshooting

### Jika data tidak muncul
```bash
# Check migration status
php artisan migrate:status

# Run migrations
php artisan migrate --force

# Run seeder
php artisan db:seed --class=BufferStockDataSeeder --force
```

### Jika perhitungan tidak akurat
1. Pastikan data historis cukup (minimal 30 hari)
2. Cek `BufferStockConfig` sesuai karakteristik bisnis
3. Jalankan sync untuk update semua perhitungan

### Performance Issues
```bash
# Add indexes untuk query yang berat
php artisan db:seed --class=AddIndexes
```

## 📝 Maintenance

### Monthly Buffer Stock Sync
```bash
php artisan schedule:run
# atau manual via API
POST /admin/inventory/buffer-stock/sync
```

### Clear Old Adjustment History (optional)
```bash
php artisan tinker
>>> App\Models\StockAdjustment::where('adjusted_at', '<', now()->subMonths(12))->delete()
```

## 🎓 Learning Resources

### Konsep Buffer Stock
- Menghitung kebutuhan stok minimum untuk menghindari stockout
- Mempertimbangkan variabilitas permintaan dan lead time
- Optimasi antara carrying cost dan shortage cost

### Forecasting Methods
- Time series analysis menggunakan 90 hari data historis
- Confidence interval untuk risk assessment
- Adjustments untuk seasonal factors (jika ada)

### Stock Opname Best Practice
- Dilakukan regularly (monthly recommended)
- Physical count vs system records
- Doc all discrepancies dan root causes

## 📞 Support

Untuk pertanyaan atau issue:
1. Check database integrity dan migration status
2. Verify dummy data seeded correctly
3. Review logs di `storage/logs/laravel.log`

---

**Version**: 1.0  
**Last Updated**: 2024  
**Status**: Production Ready ✅
