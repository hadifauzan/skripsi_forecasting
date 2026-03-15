<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterInventory;
use App\Models\MasterItem;
use App\Models\MasterItemStock;
use App\Models\TransactionPurchase;
use App\Models\TransactionSalesDetails;
use Illuminate\Http\Request;

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
        $rawMaterials = $itemStocks->through(function ($itemStock) {
            $stock = $itemStock->stock;
            $bufferStock = $itemStock->buffer_stock ?? 0;
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
            'needs_order' => $allItemStocks->filter(function ($item) {
                return ($item->stock - ($item->buffer_stock ?? 0)) < 0;
            })->count(),
            'sufficient' => $allItemStocks->filter(function ($item) {
                return ($item->stock - ($item->buffer_stock ?? 0)) >= 0;
            })->count(),
        ];

        return view('admin_inventory.raw_materials', compact(
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

        $stock = $itemStock->stock;
        $bufferStock = $itemStock->buffer_stock ?? 0;
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

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui'
        ]);
    }
}
