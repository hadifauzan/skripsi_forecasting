<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BufferStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = base_path('python/buffer_stock_per_produk.csv');
        
        // Cek apakah file CSV ada
        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: {$csvPath}");
            return;
        }
        
        // Baca file CSV
        $data = $this->readCsv($csvPath);
        
        if (empty($data)) {
            $this->command->error("File CSV kosong atau tidak dapat dibaca");
            return;
        }
        
        // Upsert data ke database
        $this->upsertData($data);
        
        $this->command->info("✅ Buffer stock data berhasil di-import ke database");
    }
    
    /**
     * Baca file CSV dan kembalikan array data
     */
    private function readCsv(string $filePath): array
    {
        $data = [];
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            return [];
        }
        
        // Skip header row
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            fclose($handle);
            return [];
        }
        
        // Baca setiap baris
        $rowCount = 0;
        while (($row = fgetcsv($handle)) !== false) {
            // Skip baris kosong
            if (count(array_filter($row)) === 0) {
                continue;
            }
            
            // Map CSV columns ke array
            $record = [
                'produk' => $this->toNullableString($row[0] ?? null),
                'max_daily_sales' => $this->toNullableFloat($row[1] ?? null),
                'avg_daily_sales' => $this->toNullableFloat($row[2] ?? null),
                'standar_deviasi' => $this->toNullableFloat($row[3] ?? null),
                'buffer_stock_unit' => $this->toNullableFloat($row[4] ?? null),
                'safety_stock_95percent_unit' => $this->toNullableFloat($row[5] ?? null),
                'rop_unit' => $this->toNullableFloat($row[6] ?? null),
                'avg_lead_time_hari' => $this->toNullableFloat($row[7] ?? null),
                'max_lead_time_hari' => $this->toNullableFloat($row[8] ?? null),
                'rumus_buffer_stock' => $this->toNullableString($row[9] ?? null),
                'rumus_rop' => $this->toNullableString($row[10] ?? null),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Skip jika produk kosong
            if (empty($record['produk'])) {
                continue;
            }
            
            $data[] = $record;
            $rowCount++;
        }
        
        fclose($handle);
        
        $this->command->info("📊 Membaca {$rowCount} data dari CSV");
        
        return $data;
    }
    
    /**
     * Upsert data ke database
     */
    private function upsertData(array $data): void
    {
        if (empty($data)) {
            return;
        }
        
        // Chunk data untuk upsert (batch 100 per kali)
        $chunks = array_chunk($data, 100);
        
        foreach ($chunks as $chunk) {
            DB::table('buffer_stock')->upsert(
                $chunk,
                ['produk'],  // Unique identifier
                [            // Columns to update
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
                    'updated_at',
                ]
            );
        }
        
        $totalRecords = count($data);
        $this->command->info("📈 {$totalRecords} data berhasil di-upsert ke tabel buffer_stock");
    }
    
    /**
     * Convert nilai ke float nullable
     */
    private function toNullableFloat($value): ?float
    {
        if (empty($value) || $value === 'null' || $value === '') {
            return null;
        }
        
        return (float) $value;
    }
    
    /**
     * Convert nilai ke string nullable
     */
    private function toNullableString($value): ?string
    {
        if (empty($value) || $value === 'null' || $value === '') {
            return null;
        }
        
        return trim((string) $value);
    }
}
