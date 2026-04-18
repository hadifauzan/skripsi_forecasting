<?php

namespace App\Services;

use App\Models\MasterItemRawMaterial;
use Illuminate\Support\Collection;

class InventoryAnalysisService
{
    /**
     * Import and process inventory data from CSV
     * Using native PHP fgetcsv
     * 
     * @param string $filePath
     * @return Collection
     */
    public function importFromCSV($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File tidak ditemukan: $filePath");
        }

        $records = collect();
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            throw new \Exception("Tidak dapat membuka file: $filePath");
        }

        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            throw new \Exception("File CSV kosong atau format tidak valid");
        }

        // Map headers
        $headerMap = array_flip($headers);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($headers)) {
                continue;
            }

            $record = [];
            foreach ($headers as $index => $header) {
                $record[$header] = $row[$index] ?? '';
            }

            if (!empty($record['item_raw_id'])) {
                $records->push([
                    'item_raw_id' => (int) $record['item_raw_id'],
                    'material_name' => $record['material_name'] ?? '',
                    'unit' => $record['unit'] ?? '',
                    'purchase_price' => $this->parsePrice($record['purchase_price'] ?? 0),
                    'current_stock' => (int) ($record['current_stock'] ?? 0),
                    'lead_time_days' => (int) ($record['lead_time_days'] ?? 7),
                    'buffer_stock' => (float) ($record['buffer_stock'] ?? 0),
                    'reorder_point' => (float) ($record['reorder_point'] ?? 0),
                    'supplier_name' => $record['supplier_name'] ?? null,
                    'created_at' => $record['created_at'] ?? null,
                    'updated_at' => $record['updated_at'] ?? null,
                    'deleted_at' => $record['deleted_at'] ?? null,
                ]);
            }
        }

        fclose($handle);
        return $records;
    }

    /**
     * Calculate average daily demand
     * Formula: Current Stock / Lead Time Days
     * 
     * @param array $item
     * @return float
     */
    public function calculateAvgDailyDemand($item)
    {
        if ($item['lead_time_days'] == 0) {
            return 0;
        }
        return round($item['current_stock'] / $item['lead_time_days'], 2);
    }

    /**
     * Calculate proposed buffer stock
     * Formula: Lead Time Days × Average Daily Demand × 0.25
     * 
     * @param array $item
     * @param float $avgDailyDemand
     * @return float
     */
    public function calculateProposedBufferStock($item, $avgDailyDemand)
    {
        $proposed = $item['lead_time_days'] * $avgDailyDemand * 0.25;
        return round($proposed, 0);
    }

    /**
     * Calculate proposed reorder point
     * Formula: (Lead Time Days × Average Daily Demand) + Buffer Stock
     * 
     * @param array $item
     * @param float $avgDailyDemand
     * @param float $proposedBufferStock
     * @return float
     */
    public function calculateProposedReorderPoint($item, $avgDailyDemand, $proposedBufferStock)
    {
        $proposed = ($item['lead_time_days'] * $avgDailyDemand) + $proposedBufferStock;
        return round($proposed, 0);
    }

    /**
     * Calculate variance between proposed and actual
     * 
     * @param float $proposed
     * @param float $actual
     * @return float
     */
    public function calculateVariance($proposed, $actual)
    {
        return round($proposed - $actual, 2);
    }

    /**
     * Process all calculations for an item
     * 
     * @param array $item
     * @return array
     */
    public function processItem($item)
    {
        $avgDailyDemand = $this->calculateAvgDailyDemand($item);
        $proposedBufferStock = $this->calculateProposedBufferStock($item, $avgDailyDemand);
        $proposedReorderPoint = $this->calculateProposedReorderPoint($item, $avgDailyDemand, $proposedBufferStock);
        
        // Calculate minimum reorder quantity (Economic Order Quantity simplified)
        // Based on buffer stock and safety factor
        $minReorderQty = max($proposedBufferStock * 2, 50); // At least 2x buffer stock or 50 units
        
        // Calculate maximum stock level
        $maxStock = $proposedReorderPoint + $minReorderQty;
        
        $bufferStockVariance = $this->calculateVariance($proposedBufferStock, $item['buffer_stock']);
        $reorderPointVariance = $this->calculateVariance($proposedReorderPoint, $item['reorder_point']);
        
        return array_merge($item, [
            'avg_daily_demand' => $avgDailyDemand,
            'buffer_stock_proposed' => $proposedBufferStock,
            'reorder_point_proposed' => $proposedReorderPoint,
            'min_reorder_qty' => $minReorderQty,
            'max_stock' => $maxStock,
            'buffer_stock_variance' => $bufferStockVariance,
            'reorder_point_variance' => $reorderPointVariance,
            'total_material_value' => $item['current_stock'] * $item['purchase_price'],
            'items_below_buffer' => $item['current_stock'] < $item['buffer_stock'] ? 1 : 0,
        ]);
    }

    /**
     * Process all items from collection
     * 
     * @param Collection $items
     * @return Collection
     */
    public function processAllItems(Collection $items)
    {
        return $items->map(function ($item) {
            return $this->processItem($item);
        });
    }

    /**
     * Generate summary statistics
     * 
     * @param Collection $processedItems
     * @return array
     */
    public function generateSummary(Collection $processedItems)
    {
        return [
            'total_materials' => $processedItems->count(),
            'total_inventory_value' => $processedItems->sum('total_material_value'),
            'avg_daily_demand' => round($processedItems->avg('avg_daily_demand'), 2),
            'avg_buffer_stock' => round($processedItems->avg('buffer_stock'), 2),
            'avg_buffer_stock_proposed' => round($processedItems->avg('buffer_stock_proposed'), 2),
            'avg_reorder_point' => round($processedItems->avg('reorder_point'), 2),
            'avg_reorder_point_proposed' => round($processedItems->avg('reorder_point_proposed'), 2),
            'items_below_buffer' => $processedItems->sum('items_below_buffer'),
            'buffer_stock_variance_mean' => round($processedItems->avg('buffer_stock_variance'), 2),
            'reorder_point_variance_mean' => round($processedItems->avg('reorder_point_variance'), 2),
            'items_variance_negative' => $processedItems->where('buffer_stock_variance', '<', 0)->count(),
            'items_variance_positive' => $processedItems->where('buffer_stock_variance', '>', 0)->count(),
        ];
    }

    /**
     * Get critical items (variance > threshold)
     * 
     * @param Collection $processedItems
     * @param float $threshold
     * @return Collection
     */
    public function getCriticalItems(Collection $processedItems, $threshold = 10)
    {
        return $processedItems
            ->where('reorder_point_variance', '>', $threshold)
            ->orWhere('reorder_point_variance', '<', -$threshold)
            ->sortByDesc('reorder_point_variance')
            ->values();
    }

    /**
     * Export analysis to array format
     * 
     * @param Collection $processedItems
     * @return array
     */
    public function exportAnalysis(Collection $processedItems)
    {
        return $processedItems->map(function ($item) {
            return [
                'item_raw_id' => $item['item_raw_id'],
                'material_name' => $item['material_name'],
                'unit' => $item['unit'],
                'current_stock' => $item['current_stock'],
                'lead_time_days' => $item['lead_time_days'],
                'avg_daily_demand' => $item['avg_daily_demand'],
                'buffer_stock' => $item['buffer_stock'],
                'buffer_stock_proposed' => $item['buffer_stock_proposed'],
                'buffer_stock_variance' => $item['buffer_stock_variance'],
                'reorder_point' => $item['reorder_point'],
                'reorder_point_proposed' => $item['reorder_point_proposed'],
                'reorder_point_variance' => $item['reorder_point_variance'],
                'supplier_name' => $item['supplier_name'],
                'purchase_price' => $item['purchase_price'],
                'total_material_value' => $item['total_material_value'],
            ];
        })->toArray();
    }

    /**
     * Parse price from string (handle comma as thousand separator)
     * 
     * @param string $price
     * @return float
     */
    private function parsePrice($price)
    {
        // Remove commas used as thousand separators
        $price = str_replace(',', '', $price);
        // Remove any currency symbols
        $price = preg_replace('/[^0-9.]/', '', $price);
        return (float) $price;
    }

    /**
     * Sync inventory analysis to database
     * Uses updateOrCreate to INSERT if not exists, UPDATE if exists
     * 
     * @param Collection $items
     * @return array
     */
    public function syncToDatabase(Collection $items)
    {
        $synced = 0;
        $failed = 0;
        $details = [];

        foreach ($items as $item) {
            try {
                // Validate required fields
                if (!isset($item['item_raw_id']) || !isset($item['buffer_stock_proposed'])) {
                    throw new \Exception("Missing required fields: item_raw_id or buffer_stock_proposed");
                }

                // Determine status dari current stock vs buffer stock
                $currentStock = (int) $item['current_stock'];
                $bufferStock = (float) $item['buffer_stock_proposed'];
                $reorderPoint = (float) $item['reorder_point_proposed'];
                $maxStock = (float) ($item['max_stock'] ?? ($reorderPoint + ($item['min_reorder_qty'] ?? 100)));
                
                // Determine stock status
                if ($currentStock <= 0) {
                    $stockStatus = 'out_of_stock';
                } elseif ($currentStock <= $bufferStock) {
                    $stockStatus = 'critical';
                } elseif ($currentStock <= $reorderPoint) {
                    $stockStatus = 'low';
                } elseif ($currentStock >= $maxStock) {
                    $stockStatus = 'overstock';
                } else {
                    $stockStatus = 'normal';
                }
                
                // Use updateOrCreate to INSERT or UPDATE
                $material = MasterItemRawMaterial::updateOrCreate(
                    ['item_raw_id' => $item['item_raw_id']], // Find by
                    [
                        'material_name' => $item['material_name'],
                        'unit' => $item['unit'],
                        'purchase_price' => (float) $item['purchase_price'],
                        'current_stock' => $currentStock,
                        'lead_time_days' => (int) $item['lead_time_days'],
                        'buffer_stock' => (int) $bufferStock,
                        'reorder_point' => (int) $reorderPoint,
                        'avg_daily_usage' => (float) $item['avg_daily_demand'],
                        'stock_status' => $stockStatus,
                        'supplier_name' => $item['supplier_name'] ?? null,
                        'updated_at' => now(),
                    ]
                );

                if ($material) {
                    $synced++;
                    $details[] = [
                        'item_raw_id' => $item['item_raw_id'],
                        'material_name' => $item['material_name'],
                        'status' => 'success',
                        'buffer_stock_old' => $item['buffer_stock'],
                        'buffer_stock_new' => $bufferStock,
                        'stock_status' => $stockStatus,
                        'action' => $material->wasRecentlyCreated ? 'created' : 'updated'
                    ];
                } else {
                    throw new \Exception("Gagal membuat/mengupdate record");
                }
            } catch (\Exception $e) {
                $failed++;
                $details[] = [
                    'item_raw_id' => $item['item_raw_id'] ?? 'Unknown',
                    'material_name' => $item['material_name'] ?? 'Unknown',
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
                
                // Log error detail
                \Log::error("Buffer stock sync error for item: ", [
                    'item' => $item,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'total' => $items->count(),
            'details' => $details,
        ];
    }
}
