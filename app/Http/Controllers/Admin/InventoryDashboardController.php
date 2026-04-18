<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterInventory;
use App\Models\MasterItem;
use App\Models\MasterItemStock;
use App\Models\MasterItemRawMaterial;
use App\Models\ProductionOrder;
use App\Models\RawMaterialIn;
use App\Models\RawMaterialOut;
use App\Models\FinishedGoodsIn;
use App\Models\FinishedGoodsOut;
use App\Models\BufferStockConfig;
use App\Models\StockAdjustment;
use App\Models\TransactionPurchase;
use App\Models\TransactionSalesDetails;
use App\Services\BufferStockCalculationService;
use App\Services\InventoryAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryDashboardController extends Controller
{
    public function index()
    {
        // Total inventori dan item
        $totalInventories = MasterInventory::count();
        $totalItems       = MasterItem::count();
        $totalStock       = MasterItemStock::sum('stock');
        $lowStockItems    = MasterItemStock::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $emptyStockItems  = MasterItemStock::where('stock', 0)->count();

        // Stok Masuk bulan ini (dari pembelian)
        $stockMasukBulanIni = TransactionPurchase::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();
        $nilaiMasukBulanIni = TransactionPurchase::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_amount');

        // Stok Keluar bulan ini (dari penjualan)
        $stockKeluarBulanIni = TransactionSalesDetails::whereHas('transactionSales', function ($q) {
                $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
            })->sum('qty');
        $nilaiKeluarBulanIni = TransactionSalesDetails::whereHas('transactionSales', function ($q) {
                $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
            })->sum('total_amount');

        // Data trend 6 bulan terakhir untuk grafik
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'month'   => $date->format('M Y'),
                'masuk'   => TransactionPurchase::whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->count(),
                'nilai_masuk' => TransactionPurchase::whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->sum('total_amount'),
                'keluar'  => TransactionSalesDetails::whereHas('transactionSales', function ($q) use ($date) {
                        $q->whereMonth('date', $date->month)->whereYear('date', $date->year);
                    })->sum('qty'),
                'nilai_keluar' => TransactionSalesDetails::whereHas('transactionSales', function ($q) use ($date) {
                        $q->whereMonth('date', $date->month)->whereYear('date', $date->year);
                    })->sum('total_amount'),
            ];
        }

        // Transaksi pembelian terbaru (stok masuk)
        $recentMasuk = TransactionPurchase::orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Transaksi penjualan terbaru per item (stok keluar)
        $recentKeluar = TransactionSalesDetails::with(['masterItem', 'transactionSales'])
            ->whereHas('transactionSales')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Daftar item dengan stok saat ini
        $itemStocks = MasterItemStock::with(['item', 'inventory'])
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin_inventory.dashboard', compact(
            'totalInventories', 'totalItems', 'totalStock', 'lowStockItems', 'emptyStockItems',
            'stockMasukBulanIni', 'nilaiMasukBulanIni',
            'stockKeluarBulanIni', 'nilaiKeluarBulanIni',
            'monthlyData', 'recentMasuk', 'recentKeluar', 'itemStocks'
        ));
    }

    public function rawMaterials(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $search = $request->get('search', '');
        $bufferStockLookup = $this->getBufferStockLookup();

        // Query builder untuk raw materials dengan item dan inventory
        $query = MasterItemStock::with(['item.category', 'inventory']);

        // Filter berdasarkan search
        if ($search) {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name_item', 'like', "%{$search}%")
                    ->orWhere('code_item', 'like', "%{$search}%");
            })->orWhereHas('inventory', function ($q) use ($search) {
                $q->where('name_inventory', 'like', "%{$search}%");
            });
        }

        // Get paginated data
        $itemStocks = $query->paginate($perPage);

        // Format data untuk view
        $rawMaterials = $itemStocks->through(function ($itemStock) use ($bufferStockLookup) {
            $stock = $itemStock->stock;
            $bufferStock = $this->resolveBufferStockFromLookup($itemStock, $bufferStockLookup);
            $stockDifference = $stock - $bufferStock;
            $needsOrder = $stockDifference < 0;

            return [
                'item_stock_id' => $itemStock->item_stock_id,
                'name_item' => $itemStock->item->name_item ?? '-',
                'code_item' => $itemStock->item->code_item ?? '',
                'inventory' => $itemStock->inventory->name_inventory ?? '-',
                'category' => $itemStock->item->category->name_category ?? '-',
                'stock' => $stock,
                'buffer_stock' => $bufferStock,
                'stock_difference' => abs($stockDifference),
                'needs_order' => $needsOrder
            ];
        });

        // Hitung summary data
        $allItemStocks = MasterItemStock::all();
        $summary = [
            'total' => $allItemStocks->count(),
            'needs_order' => $allItemStocks->filter(function ($item) use ($bufferStockLookup) {
                $bufferStock = $this->resolveBufferStockFromLookup($item, $bufferStockLookup);
                return ($item->stock - $bufferStock) < 0;
            })->count(),
            'sufficient' => $allItemStocks->filter(function ($item) use ($bufferStockLookup) {
                $bufferStock = $this->resolveBufferStockFromLookup($item, $bufferStockLookup);
                return ($item->stock - $bufferStock) >= 0;
            })->count(),
        ];

        return view('admin_inventory.finished_goods', compact(
            'rawMaterials',
            'summary',
            'perPage',
            'search'
        ));
    }

    public function getRawMaterialDetail($itemStockId)
    {
        $itemStock = MasterItemStock::with(['item.category', 'inventory'])->find($itemStockId);

        if (!$itemStock) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $bufferStockLookup = $this->getBufferStockLookup();
        $stock = $itemStock->stock;
        $bufferStock = $this->resolveBufferStockFromLookup($itemStock, $bufferStockLookup);
        $stockDifference = $stock - $bufferStock;
        $needsOrder = $stockDifference < 0;

        return response()->json([
            'item_stock_id' => $itemStock->item_stock_id,
            'name_item' => $itemStock->item->name_item ?? '-',
            'code_item' => $itemStock->item->code_item ?? '',
            'inventory' => $itemStock->inventory->name_inventory ?? '-',
            'category' => $itemStock->item->category->name_category ?? '-',
            'stock' => $stock,
            'buffer_stock' => $bufferStock,
            'stock_difference' => abs($stockDifference),
            'needs_order' => $needsOrder
        ]);
    }

    public function finishedGoodsShow($itemStockId)
    {
        $itemStock = MasterItemStock::with(['item.category', 'inventory'])->find($itemStockId);

        if (!$itemStock) {
            abort(404, 'Data tidak ditemukan');
        }

        $bufferStockLookup = $this->getBufferStockLookup();
        $stock = $itemStock->stock;
        $bufferStock = $this->resolveBufferStockFromLookup($itemStock, $bufferStockLookup);
        $stockDifference = abs($stock - $bufferStock);

        return view('admin_inventory.finished_goods_show', compact(
            'itemStock',
            'bufferStock',
            'stockDifference'
        ));
    }

    public function finishedGoodsEdit($itemStockId)
    {
        $itemStock = MasterItemStock::with(['item.category', 'inventory'])->find($itemStockId);

        if (!$itemStock) {
            abort(404, 'Data tidak ditemukan');
        }

        $bufferStockLookup = $this->getBufferStockLookup();
        $bufferStock = $this->resolveBufferStockFromLookup($itemStock, $bufferStockLookup);

        return view('admin_inventory.finished_goods_edit', compact(
            'itemStock',
            'bufferStock'
        ));
    }

    public function updateRawMaterial(Request $request, $itemStockId)
    {
        $request->validate([
            'stock' => 'required|integer|min:0|max:9999999'
        ]);

        $itemStock = MasterItemStock::find($itemStockId);

        if (!$itemStock) {
            return response()->json(['error' => 'Data tidak ditemukan', 'success' => false], 404);
        }

        $itemStock->update([
            'stock' => $request->stock
        ]);

        if (!$request->expectsJson()) {
            return redirect()
                ->route('admin.inventory.finished-goods.show', $itemStockId)
                ->with('success', 'Stok berhasil diperbarui');
        }

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui'
        ]);
    }

    public function destroyFinishedGoods($itemStockId)
    {
        $itemStock = MasterItemStock::with('item')->find($itemStockId);

        if (!$itemStock) {
            return response()->json([
                'success' => false,
                'message' => 'Data produk jadi tidak ditemukan.'
            ], 404);
        }

        $itemName = $itemStock->item->name_item ?? 'Produk';
        $itemStock->delete();

        return response()->json([
            'success' => true,
            'message' => $itemName . ' berhasil dihapus.'
        ]);
    }

    private function getBufferStockLookup(): array
    {
        $rows = DB::table('buffer_stock')
            ->select('produk', 'buffer_stock_unit')
            ->whereNotNull('produk')
            ->get();

        $lookup = [];
        foreach ($rows as $row) {
            $key = strtolower(trim((string) $row->produk));
            if ($key === '') {
                continue;
            }
            $lookup[$key] = (float) ($row->buffer_stock_unit ?? 0);
        }

        return $lookup;
    }

    private function resolveBufferStockFromLookup($itemStock, array $lookup): float
    {
        $codeKey = strtolower(trim((string) ($itemStock->item->code_item ?? '')));
        if ($codeKey !== '' && array_key_exists($codeKey, $lookup)) {
            return (float) $lookup[$codeKey];
        }

        $nameKey = strtolower(trim((string) ($itemStock->item->name_item ?? '')));
        if ($nameKey !== '' && array_key_exists($nameKey, $lookup)) {
            return (float) $lookup[$nameKey];
        }

        return (float) ($itemStock->buffer_stock ?? 0);
    }

    /**
     * GET: /admin/inventory/buffer-stock/raw-materials
     * Display raw materials with buffer stock calculations from CSV analysis
     */
    public function bufferStockRawMaterials(Request $request)
    {
        $analysisService = new InventoryAnalysisService();
        $perPage = (int) $request->get('per_page', 10);
        $search = $request->get('search', '');
        $useCSV = true;

        try {
            $csvPath = storage_path('app/python/master_items_raw_material.csv');
            if (!file_exists($csvPath)) {
                $csvPath = base_path('python/master_items_raw_material.csv');
            }
            if (!file_exists($csvPath)) {
                $csvPath = public_path('master_items_raw_material.csv');
            }
            if (!file_exists($csvPath)) {
                throw new \Exception('File master_items_raw_material.csv tidak ditemukan.');
            }

            $csvData = $analysisService->importFromCSV($csvPath);

            $filteredData = $csvData;
            if ($search !== '') {
                $keyword = mb_strtolower($search);
                $filteredData = $csvData->filter(function ($item) use ($keyword) {
                    return str_contains(mb_strtolower((string) ($item['item_raw_id'] ?? '')), $keyword)
                        || str_contains(mb_strtolower((string) ($item['material_name'] ?? '')), $keyword)
                        || str_contains(mb_strtolower((string) ($item['unit'] ?? '')), $keyword)
                        || str_contains(mb_strtolower((string) ($item['supplier_name'] ?? '')), $keyword);
                })->values();
            }

            $currentPage = max(1, (int) $request->get('page', 1));
            $total = $filteredData->count();
            $itemsForPage = $filteredData->forPage($currentPage, $perPage)->values();

            $materialData = new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsForPage,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            $summary = [
                'total_materials' => $csvData->count(),
                'total_inventory_value' => $csvData->sum(fn($m) => ((float) ($m['current_stock'] ?? 0)) * ((float) ($m['purchase_price'] ?? 0))),
                'avg_buffer_stock' => round((float) ($csvData->avg('buffer_stock') ?? 0), 2),
                'avg_lead_time' => round((float) ($csvData->avg('lead_time_days') ?? 0), 2),
                'empty_stock' => $csvData->filter(fn($m) => (int) ($m['current_stock'] ?? 0) <= 0)->count(),
                'items_below_buffer' => $csvData->filter(fn($m) => (float) ($m['current_stock'] ?? 0) < (float) ($m['buffer_stock'] ?? 0))->count(),
            ];
        } catch (\Exception $e) {
            return back()->with('error', 'Error membaca CSV: ' . $e->getMessage());
        }

        return view('admin_inventory.buffer_stock_raw_materials', compact(
            'materialData',
            'summary',
            'perPage',
            'search',
            'useCSV'
        ));
    }

    /**
     * GET: /admin/inventory/forecasting/demand
     * Display demand forecasting for finished goods
     */
    public function demandForecasting(Request $request)
    {
        $forecastDays = (int) $request->get('forecast_days', 30);
        $summaryTable = 'arima_forecast_summaries';
        $categoryTable = 'arima_forecast_mae_category_summaries';

        if (DB::getSchemaBuilder()->hasTable($summaryTable) && DB::getSchemaBuilder()->hasTable($categoryTable)) {
            $masterItemTable = (new MasterItem())->getTable();

            $rawForecastData = DB::table($summaryTable . ' as afs')
                ->leftJoin($masterItemTable . ' as mi', 'mi.code_item', '=', 'afs.produk')
                ->select([
                    'afs.produk',
                    'afs.arima_order',
                    'afs.mae',
                    'afs.rmse',
                    'afs.mape_percentage',
                    'afs.stationary',
                    'afs.adf_p_value',
                    'afs.kategori_mae',
                    'afs.updated_at',
                    'mi.item_id',
                    'mi.code_item',
                    'mi.name_item',
                    'mi.costprice_item',
                    'mi.sellingprice_item',
                    'mi.current_inventory',
                ])
                ->orderByRaw("CASE afs.kategori_mae WHEN 'rendah' THEN 1 WHEN 'menengah' THEN 2 WHEN 'tinggi' THEN 3 ELSE 4 END")
                ->orderBy('afs.mae', 'asc')
                ->get();

            $forecastData = $rawForecastData->map(function ($row) {
                return [
                    'item_id' => $row->item_id,
                    'code_item' => $row->code_item ?: $row->produk,
                    'name_item' => $row->name_item ?: $row->produk,
                    'costprice_item' => $row->costprice_item ?? 0,
                    'sellingprice_item' => $row->sellingprice_item ?? 0,
                    'current_inventory' => $row->current_inventory ?? 0,
                    'produk' => $row->produk,
                    'arima_order' => $row->arima_order,
                    'mae' => (float) $row->mae,
                    'rmse' => (float) $row->rmse,
                    'mape_percentage' => (float) $row->mape_percentage,
                    'stationary' => is_null($row->stationary) ? '-' : ($row->stationary ? 'Ya' : 'Tidak'),
                    'adf_p_value' => is_null($row->adf_p_value) ? null : (float) $row->adf_p_value,
                    'kategori_mae' => $row->kategori_mae,
                    'synced_at' => $row->updated_at,
                ];
            });

            $categorySummary = DB::table($categoryTable)
                ->select('kategori_mae', 'jumlah_produk', 'mae_rata_rata', 'rmse_rata_rata', 'mape_rata_rata')
                ->orderByRaw("CASE kategori_mae WHEN 'rendah' THEN 1 WHEN 'menengah' THEN 2 WHEN 'tinggi' THEN 3 ELSE 4 END")
                ->get()
                ->keyBy('kategori_mae');

            $summary = [
                'total_items' => $forecastData->count(),
                'forecast_period_days' => $forecastDays,
                'source' => 'ARIMA Seeder CSV',
                'updated_at' => $rawForecastData->max('updated_at'),
                'avg_mae' => round((float) $forecastData->avg('mae'), 4),
                'avg_rmse' => round((float) $forecastData->avg('rmse'), 4),
                'avg_mape' => round((float) $forecastData->avg('mape_percentage'), 4),
                'kategori_rendah' => (int) optional($categorySummary->get('rendah'))->jumlah_produk,
                'kategori_menengah' => (int) optional($categorySummary->get('menengah'))->jumlah_produk,
                'kategori_tinggi' => (int) optional($categorySummary->get('tinggi'))->jumlah_produk,
            ];
        } else {
            $service = new BufferStockCalculationService();

            // Fallback jika tabel ARIMA belum ada
            $items = MasterItem::where('status_item', 'active')->get();
            $forecastData = $items->map(function ($item) use ($service, $forecastDays) {
                $forecast = $service->getForecastDemand($item->item_id, $forecastDays);

                return array_merge([
                    'item_id' => $item->item_id,
                    'code_item' => $item->code_item,
                    'name_item' => $item->name_item,
                    'costprice_item' => $item->costprice_item,
                    'sellingprice_item' => $item->sellingprice_item,
                    'current_inventory' => $item->current_inventory ?? 0,
                    'produk' => $item->code_item,
                    'arima_order' => '-',
                    'mae' => 0,
                    'rmse' => 0,
                    'mape_percentage' => 0,
                    'stationary' => '-',
                    'adf_p_value' => null,
                    'kategori_mae' => '-',
                    'synced_at' => null,
                ], $forecast);
            });

            $summary = [
                'total_items' => $items->count(),
                'forecast_period_days' => $forecastDays,
                'source' => 'Fallback Moving Average',
                'updated_at' => null,
                'avg_mae' => 0,
                'avg_rmse' => 0,
                'avg_mape' => 0,
                'kategori_rendah' => 0,
                'kategori_menengah' => 0,
                'kategori_tinggi' => 0,
            ];
        }

        return view('admin_inventory.demand_forecasting', compact(
            'forecastData',
            'summary',
            'forecastDays'
        ));
    }

    /**
     * GET: /admin/inventory/stock-opname
     * Display stock opname and adjustment history
     */
    public function stockOpname(Request $request)
    {
        $daysBack = (int) $request->get('days', 30);
        $startDate = Carbon::now()->subDays($daysBack);

        // Stock adjustments
        $adjustments = StockAdjustment::where('adjusted_at', '>=', $startDate)
            ->with('rawMaterial', 'inventory', 'adjustedByUser')
            ->orderBy('adjusted_at', 'desc')
            ->paginate(20);

        // Summary by type
        $adjustmentSummary = StockAdjustment::where('adjusted_at', '>=', $startDate)
            ->selectRaw('adjustment_type, COUNT(*) as count, SUM(qty_difference) as total_qty')
            ->groupBy('adjustment_type')
            ->pluck('total_qty', 'adjustment_type');

        // Summary by reason
        $adjustmentByReason = StockAdjustment::where('adjusted_at', '>=', $startDate)
            ->selectRaw('reason, COUNT(*) as count, SUM(qty_difference) as total_qty')
            ->groupBy('reason')
            ->pluck('total_qty', 'reason');

        // Materials with adjustments
        $materialsWithAdjustments = StockAdjustment::where('adjusted_at', '>=', $startDate)
            ->selectRaw('item_id, item_type, COUNT(*) as adjustment_count, SUM(qty_difference) as total_adjustment')
            ->groupBy('item_id', 'item_type')
            ->get()
            ->sortByDesc('adjustment_count');

        $summary = [
            'period_days' => $daysBack,
            'total_adjustments' => $adjustments->total(),
            'adjustment_types' => $adjustmentSummary->toArray(),
            'adjustment_reasons' => $adjustmentByReason->take(5)->toArray()
        ];

        return view('admin_inventory.stock_opname', compact(
            'adjustments',
            'materialsWithAdjustments',
            'summary',
            'daysBack'
        ));
    }

    /**
     * GET: /admin/inventory/production-overview
     * Display production orders and raw material tracking
     */
    public function productionOverview(Request $request)
    {
        $daysBack = (int) $request->get('days', 30);
        $startDate = Carbon::now()->subDays($daysBack);

        // Recent production orders
        $productionOrders = ProductionOrder::where('planned_date', '>=', $startDate)
            ->with('item')
            ->orderBy('planned_date', 'desc')
            ->paginate(15);

        // Raw materials in/out summary
        $rawMaterialInSummary = RawMaterialIn::where('received_date', '>=', $startDate)
            ->selectRaw('item_raw_id, COUNT(*) as receipt_count, SUM(qty_received) as total_received')
            ->groupBy('item_raw_id')
            ->with('rawMaterial')
            ->get();

        $rawMaterialOutSummary = RawMaterialOut::where('issued_date', '>=', $startDate)
            ->selectRaw('item_raw_id, COUNT(*) as usage_count, SUM(qty_issued) as total_used')
            ->groupBy('item_raw_id')
            ->with('rawMaterial')
            ->get();

        // Production status breakdown
        $productionStatus = ProductionOrder::where('planned_date', '>=', $startDate)
            ->selectRaw('status, COUNT(*) as count, SUM(qty_planned) as total_qty')
            ->groupBy('status')
            ->pluck('total_qty', 'status');

        // Finished goods in/out
        $finishedGoodsIn = FinishedGoodsIn::where('received_date', '>=', $startDate)
            ->selectRaw('item_id, COUNT(*) as batch_count, SUM(qty_received) as total_produced')
            ->groupBy('item_id')
            ->with('item')
            ->get();

        $finishedGoodsOut = FinishedGoodsOut::where('out_date', '>=', $startDate)
            ->selectRaw('item_id, COUNT(*) as transaction_count, SUM(qty_out) as total_sold')
            ->groupBy('item_id')
            ->with('item')
            ->get();

        $summary = [
            'period_days' => $daysBack,
            'total_production_orders' => $productionOrders->total(),
            'production_status' => $productionStatus->toArray(),
            'total_raw_material_in' => $rawMaterialInSummary->sum('total_received'),
            'total_raw_material_out' => $rawMaterialOutSummary->sum('total_used'),
            'total_finished_goods_in' => $finishedGoodsIn->sum('total_produced'),
            'total_finished_goods_out' => $finishedGoodsOut->sum('total_sold')
        ];

        return view('admin_inventory.production_overview', compact(
            'productionOrders',
            'rawMaterialInSummary',
            'rawMaterialOutSummary',
            'finishedGoodsIn',
            'finishedGoodsOut',
            'summary',
            'daysBack'
        ));
    }

    /**
     * GET: /admin/inventory/buffer-stock/details/{itemRawId}
     * Get detailed buffer stock calculation for a specific material
     */
    public function bufferStockDetail($itemRawId)
    {
        $service = new BufferStockCalculationService();
        $material = MasterItemRawMaterial::find($itemRawId);

        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        $calculation = $service->calculateBufferStock($itemRawId);
        $adjustmentAnalysis = $service->getStockAdjustmentAnalysis($itemRawId);

        // Get historical usage data for last 30 days
        $usageHistory = RawMaterialOut::where('item_raw_id', $itemRawId)
            ->where('issued_date', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(issued_date) as date, SUM(qty_issued) as daily_usage')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get receipt history for last 30 days
        $receiptHistory = RawMaterialIn::where('item_raw_id', $itemRawId)
            ->where('received_date', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(received_date) as date, SUM(qty_received) as daily_receipt')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'material' => $material,
            'calculation' => $calculation,
            'adjustment_analysis' => $adjustmentAnalysis,
            'usage_history' => $usageHistory,
            'receipt_history' => $receiptHistory
        ]);
    }

    /**
     * PUT: /admin/inventory/buffer-stock/raw-materials/{itemRawId}
     * Update raw material fields from buffer stock table action
     */
    public function updateBufferStockRawMaterial(Request $request, $itemRawId)
    {
        $validated = $request->validate([
            'material_name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'lead_time_days' => 'required|integer|min:0|max:3650',
            'buffer_stock' => 'required|numeric|min:0',
            'supplier_name' => 'nullable|string|max:255',
        ]);

        $bufferStock = (float) $validated['buffer_stock'];
        $currentStock = (float) $validated['current_stock'];

        $stockStatus = $currentStock <= 0
            ? 'critical'
            : ($currentStock < $bufferStock ? 'low' : 'normal');

        $payload = [
            'material_name' => $validated['material_name'],
            'unit' => $validated['unit'] ?? null,
            'purchase_price' => $validated['purchase_price'],
            'current_stock' => $currentStock,
            'lead_time_days' => (int) $validated['lead_time_days'],
            'buffer_stock' => $bufferStock,
            'supplier_name' => $validated['supplier_name'] ?? null,
            'stock_status' => $stockStatus,
            'avg_daily_usage' => 0,
            'reorder_point' => $bufferStock,
        ];

        $material = MasterItemRawMaterial::updateOrCreate(
            ['item_raw_id' => $itemRawId],
            $payload
        );

        return response()->json([
            'success' => true,
            'message' => 'Data bahan baku berhasil diperbarui.',
            'data' => $material
        ]);
    }

    /**
     * DELETE: /admin/inventory/buffer-stock/raw-materials/{itemRawId}
     * Delete raw material with relation safety checks
     */
    public function destroyBufferStockRawMaterial($itemRawId)
    {
        $material = MasterItemRawMaterial::find($itemRawId);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Data bahan baku tidak ditemukan di database.'
            ], 404);
        }

        $hasReferences = $material->rawMaterialIn()->exists()
            || $material->rawMaterialOut()->exists()
            || $material->billOfMaterials()->exists()
            || $material->stockAdjustments()->exists();

        if ($hasReferences) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat dihapus karena masih dipakai pada transaksi atau relasi lain.'
            ], 422);
        }

        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data bahan baku berhasil dihapus.'
        ]);
    }

    /**
     * POST: /admin/inventory/buffer-stock/sync
     * Sync all buffer stocks calculations to database
     */
    public function syncBufferStocks()
    {
        try {
            $service = new BufferStockCalculationService();
            $result = $service->syncAllBufferStocks();

            // Check if all items failed
            if ($result['updated'] === 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal! Semua {$result['total_materials']} bahan gagal diperbarui. Silakan periksa logs untuk detail error.",
                    'data' => $result
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil! {$result['updated']} dari {$result['total_materials']} bahan diperbarui.",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error("Buffer stock sync error: " . $e->getMessage(), $e->getTrace());
            
            return response()->json([
                'success' => false,
                'message' => "Error sinkronisasi: " . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * GET: /admin/inventory/buffer-stock/items-to-order
     * Get list of items that need to be ordered
     */
    public function itemsToOrder()
    {
        $service = new BufferStockCalculationService();

        $materials = MasterItemRawMaterial::where('stock_status', 'critical')
            ->orWhere(function ($q) {
                $q->where('stock_status', 'low')->where('current_stock', '<', 100);
            })
            ->get();

        $itemsNeedingOrder = $materials->map(function ($material) use ($service) {
            $calculation = $service->calculateBufferStock($material->item_raw_id);
            $shortageQty = $calculation['reorder_point'] - $material->current_stock;

            return array_merge($calculation, [
                'shortage_quantity' => max(0, $shortageQty),
                'estimated_cost' => max(0, $shortageQty) * $material->purchase_price,
                'min_order_qty' => max($calculation['min_reorder_qty'], round($shortageQty, 0))
            ]);
        });

        $summary = [
            'total_items_low' => $itemsNeedingOrder->count(),
            'total_shortage_qty' => $itemsNeedingOrder->sum('shortage_quantity'),
            'total_estimated_cost' => $itemsNeedingOrder->sum('estimated_cost')
        ];

        return response()->json([
            'success' => true,
            'items' => $itemsNeedingOrder,
            'summary' => $summary
        ]);
    }

    /**
     * POST: /admin/inventory/buffer-stock/sync-from-csv
     * Sync buffer stock calculations from CSV to database
     */
    public function syncBufferStockFromCSV(Request $request)
    {
        $analysisService = new InventoryAnalysisService();

        try {
            $csvPath = storage_path('app/python/master_items_raw_material.csv');
            if (!file_exists($csvPath)) {
                $csvPath = base_path('python/master_items_raw_material.csv');
            }
            if (!file_exists($csvPath)) {
                $csvPath = public_path('master_items_raw_material.csv');
            }

            // Import and process CSV data
            $csvData = $analysisService->importFromCSV($csvPath);
            $processedData = $analysisService->processAllItems($csvData);

            // Sync to database (using updateOrCreate, so all should succeed)
            $result = $analysisService->syncToDatabase($processedData);

            // Log sync details
            Log::info('Buffer Stock CSV Sync', [
                'synced' => $result['synced'],
                'failed' => $result['failed'],
                'total' => $result['total'],
                'timestamp' => now()
            ]);

            // Check if all succeeded
            if ($result['failed'] === 0) {
                return response()->json([
                    'success' => true,
                    'message' => "✅ Sinkronisasi berhasil! Semua {$result['total']} bahan telah diperbarui.",
                    'data' => $result,
                    'redirect_url' => route('admin.inventory.buffer-stock.raw-materials')
                ]);
            } else {
                // Some items failed - log but still return success with warning
                $failedItems = array_filter($result['details'], fn($d) => $d['status'] !== 'success');
                
                Log::warning('Some items failed during sync', [
                    'synced' => $result['synced'],
                    'failed' => $result['failed'],
                    'failed_items' => array_slice($failedItems, 0, 5)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "⚠️ Sinkronisasi selesai dengan peringatan: {$result['synced']} berhasil, {$result['failed']} gagal dari {$result['total']} bahan. Periksa logs untuk detail.",
                    'data' => $result,
                    'redirect_url' => route('admin.inventory.buffer-stock.raw-materials')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Buffer Stock CSV Sync Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sinkronisasi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET: /admin/inventory/buffer-stock/export-analysis
     * Export inventory analysis to Excel
     */
    public function exportInventoryAnalysis()
    {
        $analysisService = new InventoryAnalysisService();

        try {
            $csvPath = storage_path('app/python/master_items_raw_material.csv');
            if (!file_exists($csvPath)) {
                $csvPath = base_path('python/master_items_raw_material.csv');
            }
            if (!file_exists($csvPath)) {
                $csvPath = public_path('master_items_raw_material.csv');
            }

            // Import and process CSV data
            $csvData = $analysisService->importFromCSV($csvPath);
            $processedData = $analysisService->processAllItems($csvData);
            $exportData = $analysisService->exportAnalysis($processedData);

            // Create Excel file
            $filename = 'inventory_analysis_' . now()->format('Ymd_His') . '.csv';
            
            $file = fopen('php://memory', 'w');
            fputcsv($file, array_keys($exportData[0]));
            
            foreach ($exportData as $row) {
                fputcsv($file, $row);
            }
            
            rewind($file);
            $content = stream_get_contents($file);
            fclose($file);

            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\""
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting: ' . $e->getMessage());
        }
    }

    /**
     * GET: /admin/inventory/buffer-stock/import-status
     * Show import status from CSV
     */
    public function bufferStockImportStatus()
    {
        $analysisService = new InventoryAnalysisService();

        try {
            $csvPath = storage_path('app/python/master_items_raw_material.csv');
            if (!file_exists($csvPath)) {
                $csvPath = base_path('python/master_items_raw_material.csv');
            }
            if (!file_exists($csvPath)) {
                $csvPath = public_path('master_items_raw_material.csv');
            }

            // Import and process CSV data
            $csvData = $analysisService->importFromCSV($csvPath);
            $processedData = $analysisService->processAllItems($csvData);
            $summary = $analysisService->generateSummary($processedData);
            $criticalItems = $analysisService->getCriticalItems($processedData);

            return view('admin_inventory.buffer_stock_import_status', compact(
                'summary',
                'criticalItems',
                'csvPath'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

