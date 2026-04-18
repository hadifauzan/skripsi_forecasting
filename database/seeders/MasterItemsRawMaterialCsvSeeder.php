<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterItemsRawMaterialCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = storage_path('app/python/master_items_raw_material.csv');

        if (!file_exists($csvPath)) {
            $csvPath = base_path('python/master_items_raw_material.csv');
        }

        if (!file_exists($csvPath)) {
            $csvPath = public_path('master_items_raw_material.csv');
        }

        if (!file_exists($csvPath)) {
            $this->command?->warn('CSV tidak ditemukan: master_items_raw_material.csv');
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command?->error('Gagal membuka file CSV.');
            return;
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->command?->error('Header CSV tidak valid.');
            return;
        }

        $now = now();
        $rows = [];

        while (($line = fgetcsv($handle)) !== false) {
            if (count($line) < count($headers)) {
                continue;
            }

            $data = array_combine($headers, $line);
            if (!$data || empty($data['item_raw_id'])) {
                continue;
            }

            $currentStock = (int) ($data['current_stock'] ?? 0);
            $bufferStock = (int) round((float) ($data['buffer_stock'] ?? 0));
            $reorderPoint = (int) ($data['reorder_point'] ?? max($bufferStock * 2, 0));

            $stockStatus = 'normal';
            if ($currentStock <= 0) {
                $stockStatus = 'out_of_stock';
            } elseif ($currentStock <= $bufferStock) {
                $stockStatus = 'critical';
            } elseif ($currentStock <= $reorderPoint) {
                $stockStatus = 'low';
            } elseif ($currentStock > ($reorderPoint + max($bufferStock, 1))) {
                $stockStatus = 'overstock';
            }

            $rows[] = [
                'item_raw_id' => (int) $data['item_raw_id'],
                'material_name' => (string) ($data['material_name'] ?? ''),
                'unit' => (string) ($data['unit'] ?? ''),
                'purchase_price' => (float) ($data['purchase_price'] ?? 0),
                'current_stock' => $currentStock,
                'avg_daily_usage' => (float) ($data['avg_daily_usage'] ?? 0),
                'last_reorder_date' => $this->parseDate($data['last_reorder_date'] ?? null),
                'stock_status' => $stockStatus,
                'lead_time_days' => (int) ($data['lead_time_days'] ?? 0),
                'buffer_stock' => $bufferStock,
                'reorder_point' => $reorderPoint,
                'supplier_name' => $data['supplier_name'] !== '' ? $data['supplier_name'] : null,
                'created_at' => $this->parseDateTime($data['created_at'] ?? null) ?? $now,
                'updated_at' => $this->parseDateTime($data['updated_at'] ?? null) ?? $now,
                'deleted_at' => $this->parseDateTime($data['deleted_at'] ?? null),
            ];
        }

        fclose($handle);

        if (empty($rows)) {
            $this->command?->warn('Tidak ada data valid pada CSV.');
            return;
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('master_items_raw_material')->upsert(
                $chunk,
                ['item_raw_id'],
                [
                    'material_name',
                    'unit',
                    'purchase_price',
                    'current_stock',
                    'avg_daily_usage',
                    'last_reorder_date',
                    'stock_status',
                    'lead_time_days',
                    'buffer_stock',
                    'reorder_point',
                    'supplier_name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            );
        }

        $this->command?->info('Master raw material CSV berhasil diimpor ke database. Total: ' . count($rows));
    }

    private function parseDateTime(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function parseDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
