# Buffer Stock Database Integration - Setup Guide

## Deskripsi
Panduan lengkap untuk mengimport data buffer stock dari CSV ke database Laravel menggunakan migration dan seeder.

---

## File yang Dibuat

### 1. Migration: `2026_04_16_000001_create_buffer_stock_table.php`
**Lokasi:** `database/migrations/`

Membuat tabel `buffer_stock` dengan kolom:
- `id` - Primary key
- `produk` - Nama produk (unique)
- `max_daily_sales` - Pemakaian harian maksimum (persentil 95%)
- `avg_daily_sales` - Pemakaian rata-rata harian
- `standar_deviasi` - Standard deviation dari pemakaian
- `buffer_stock_unit` - Buffer stock dalam unit
- `safety_stock_95percent_unit` - Safety stock dengan service level 95%
- `rop_unit` - Reorder Point dalam unit
- `avg_lead_time_hari` - Lead time rata-rata
- `max_lead_time_hari` - Lead time maksimum
- `rumus_buffer_stock` - Formula buffer stock
- `rumus_rop` - Formula ROP
- `timestamps` - created_at dan updated_at

### 2. Seeder: `BufferStockSeeder.php`
**Lokasi:** `database/seeders/`

Membaca file CSV (`python/buffer_stock_per_produk.csv`) dan mengimport data ke database dengan fitur:
- Validasi file CSV
- Smart type conversion (float, string)
- Batch processing (chunk 100 records)
- Upsert logic (update jika produk sudah ada, insert jika baru)
- Error handling dan feedback

### 3. DatabaseSeeder Update
Mendaftarkan `BufferStockSeeder::class` dalam queue seeding

---

## Cara Penggunaan

### Step 1: Generate CSV dari Python
Jalankan notebook atau script Python untuk generate CSV:
```bash
cd python
python buffer_stock_calculation.py
# atau run notebook: buffer_stock_calculation.ipynb
```

Pastikan file `python/buffer_stock_per_produk.csv` sudah ada.

### Step 2: Run Migration
```bash
php artisan migrate
```

Ini akan membuat tabel `buffer_stock` dengan struktur yang tepat.

### Step 3: Run Seeder (2 opsi)

**Opsi A: Seed saja BufferStockSeeder**
```bash
php artisan db:seed --class=BufferStockSeeder
```

**Opsi B: Seed semua (termasuk BufferStockSeeder)**
```bash
php artisan db:seed
```

**Opsi C: Refresh database + reseed**
```bash
php artisan migrate:refresh --seed
```

---

## Struktur Data CSV

File `buffer_stock_per_produk.csv` harus memiliki header dan kolom dalam urutan:

| No | Kolom | Tipe | Contoh |
|---|---|---|---|
| 1 | Produk | String | "Gentle Baby" |
| 2 | Max_Daily_Sales | Float | 150.50 |
| 3 | Avg_Daily_Sales | Float | 75.25 |
| 4 | Standar_Deviasi | Float | 35.10 |
| 5 | Buffer_Stock_Unit | Float | 245.80 |
| 6 | Safety_Stock_95percent_Unit | Float | 92.33 |
| 7 | ROP_Unit | Float | 498.20 |
| 8 | Avg_Lead_Time_Hari | Float | 5.4 |
| 9 | Max_Lead_Time_Hari | Float | 7.0 |
| 10 | Rumus_Buffer_Stock | String | "(Max Daily Sales x 7) - (Avg Daily Sales x 5.4)" |
| 11 | Rumus_ROP | String | "(Avg Daily Sales x 5.4) + Safety Stock" |

---

## Catatan Penting

### Upsert Logic
Seeder menggunakan `DB::table()->upsert()` yang berarti:
- Jika produk **belum ada** → Insert baru
- Jika produk **sudah ada** → Update nilai-nilainya

Ini memungkinkan seeder dijalankan berkali-kali tanpa duplikasi data.

### Format CSV
- Pastikan CSV menggunakan encoding **UTF-8 with BOM** (yang dihasil dari Python)
- Delimiter default: `,` (koma)
- Enclosed quote: `"` (double quote)

### Batch Processing
Data diproses per 100 baris untuk efisiensi memory.

---

## Troubleshooting

### Error: "File CSV tidak ditemukan"
Pastikan file `python/buffer_stock_per_produk.csv` sudah dihasilkan dan ada di lokasi yang tepat.

### Error: "SQLSTATE[42S22]: Column not found"
Jalankan migration terlebih dahulu:
```bash
php artisan migrate
```

### Data tidak terupdate
Pastikan kolom `produk` di CSV sesuai dengan yang sudah di database (case-sensitive).

### Melihat Data yang Diimport
Query database:
```php
// Di tinker atau controller
$bufferStocks = DB::table('buffer_stock')->get();
$bufferStocks = DB::table('buffer_stock')->where('produk', 'Gentle Baby')->first();
```

---

## Contoh Select Query

```php
// Get all buffer stock
$bufferStocks = DB::table('buffer_stock')->get();

// Get by product name
$product = DB::table('buffer_stock')
    ->where('produk', 'Gentle Baby')
    ->first();

// Get top 10 by ROP
$topRop = DB::table('buffer_stock')
    ->orderBy('rop_unit', 'desc')
    ->limit(10)
    ->get();

// Get top 10 by Buffer Stock
$topBuffer = DB::table('buffer_stock')
    ->orderBy('buffer_stock_unit', 'desc')
    ->limit(10)
    ->get();

// Aggregate statistics
$stats = DB::table('buffer_stock')
    ->select(
        DB::raw('COUNT(*) as total_produk'),
        DB::raw('SUM(buffer_stock_unit) as total_buffer_stock'),
        DB::raw('AVG(buffer_stock_unit) as avg_buffer_stock'),
        DB::raw('SUM(rop_unit) as total_rop'),
        DB::raw('AVG(rop_unit) as avg_rop')
    )
    ->first();
```

---

## Integrasi dengan Model

Buat Model untuk buffer stock (optional):

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BufferStock extends Model
{
    protected $table = 'buffer_stock';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'produk',
        'max_daily_sales',
        'avg_daily_sales',
        'standar_deviasi',
        'buffer_stock_unit',
        'safety_stock_95percent_unit',
        'rop_unit',
        'avg_lead_time_hari',
        'max_lead_time_hari',
        'rumus_buffer_stock',
        'rumus_rop',
    ];
    
    protected $casts = [
        'max_daily_sales' => 'float',
        'avg_daily_sales' => 'float',
        'standar_deviasi' => 'float',
        'buffer_stock_unit' => 'float',
        'safety_stock_95percent_unit' => 'float',
        'rop_unit' => 'float',
        'avg_lead_time_hari' => 'float',
        'max_lead_time_hari' => 'float',
    ];
}
```

Kemudian gunakan di code:
```php
$bufferStocks = BufferStock::all();
$product = BufferStock::where('produk', 'Gentle Baby')->first();
```

---

## Next Steps

1. ✅ Generate CSV dari Python script
2. ✅ Run migration
3. ✅ Run seeder
4. ✅ Verify data di database
5. ⏭️ Buat Controller untuk display buffer stock
6. ⏭️ Buat View untuk dashboard inventory
7. ⏭️ Integrasi dengan reorder alert system

---

**Created:** April 16, 2026
