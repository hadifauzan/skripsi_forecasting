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
}
