<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArimaForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $summaryPath = base_path('python/arima_forecast_summary_per_produk.csv');
        $categoryPath = base_path('python/arima_forecast_mae_kategori_ringkas.csv');

        if (!File::exists($summaryPath)) {
            $this->command?->error("File tidak ditemukan: {$summaryPath}");
            return;
        }

        if (!File::exists($categoryPath)) {
            $this->command?->error("File tidak ditemukan: {$categoryPath}");
            return;
        }

        $summaryRows = $this->readCsv($summaryPath);
        $categoryRows = $this->readCsv($categoryPath);

        $summaryPayload = [];
        foreach ($summaryRows as $row) {
            $stationaryRaw = $row['Stationary'] ?? null;
            $stationary = null;

            if ($stationaryRaw !== null && $stationaryRaw !== '') {
                $normalized = mb_strtolower(trim((string) $stationaryRaw));
                if (in_array($normalized, ['ya', 'yes', 'true', '1'], true)) {
                    $stationary = true;
                } elseif (in_array($normalized, ['tidak', 'no', 'false', '0'], true)) {
                    $stationary = false;
                }
            }

            $summaryPayload[] = [
                'produk' => trim((string) ($row['Produk'] ?? '')),
                'arima_order' => trim((string) ($row['ARIMA Order'] ?? '')),
                'mae' => (float) ($row['MAE'] ?? 0),
                'rmse' => (float) ($row['RMSE'] ?? 0),
                'mape_percentage' => (float) ($row['MAPE (%)'] ?? 0),
                'stationary' => $stationary,
                'adf_p_value' => $this->toNullableFloat($row['ADF p-value'] ?? null),
                'kategori_mae' => trim((string) ($row['Kategori MAE'] ?? '')),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        $summaryPayload = array_values(array_filter($summaryPayload, function (array $row) {
            return $row['produk'] !== '' && $row['kategori_mae'] !== '';
        }));

        $categoryPayload = [];
        foreach ($categoryRows as $row) {
            $kategoriMae = trim((string) ($row['Kategori MAE'] ?? ''));
            if ($kategoriMae === '') {
                continue;
            }

            $categoryPayload[] = [
                'kategori_mae' => $kategoriMae,
                'jumlah_produk' => (int) ($row['jumlah_produk'] ?? 0),
                'mae_rata_rata' => (float) ($row['mae_rata_rata'] ?? 0),
                'rmse_rata_rata' => (float) ($row['rmse_rata_rata'] ?? 0),
                'mape_rata_rata' => (float) ($row['mape_rata_rata'] ?? 0),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        DB::transaction(function () use ($summaryPayload, $categoryPayload) {
            if (!empty($summaryPayload)) {
                DB::table('arima_forecast_summaries')->upsert(
                    $summaryPayload,
                    ['produk'],
                    ['arima_order', 'mae', 'rmse', 'mape_percentage', 'stationary', 'adf_p_value', 'kategori_mae', 'updated_at']
                );
            }

            if (!empty($categoryPayload)) {
                DB::table('arima_forecast_mae_category_summaries')->upsert(
                    $categoryPayload,
                    ['kategori_mae'],
                    ['jumlah_produk', 'mae_rata_rata', 'rmse_rata_rata', 'mape_rata_rata', 'updated_at']
                );
            }
        });

        $this->command?->info('ARIMA forecast CSV berhasil diimport ke database.');
        $this->command?->line(' - arima_forecast_summaries: ' . count($summaryPayload) . ' baris');
        $this->command?->line(' - arima_forecast_mae_category_summaries: ' . count($categoryPayload) . ' baris');
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function readCsv(string $path): array
    {
        $rows = [];

        if (($handle = fopen($path, 'r')) === false) {
            return $rows;
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            return $rows;
        }

        // Buang UTF-8 BOM jika ada pada header pertama.
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) !== count($headers)) {
                continue;
            }

            $row = array_combine($headers, $data);
            if ($row === false) {
                continue;
            }

            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function toNullableFloat(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '') {
            return null;
        }

        return (float) $stringValue;
    }
}
