<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\TransactionSales;
use App\Models\TransactionSalesDetails;
use App\Models\MasterItem;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get monthly sales data for the last 6 months
        $monthlySales = TransactionSales::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('MONTH(date) as month'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_sales')
        )
        ->where('date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Format data for chart
        $chartData = [
            'labels' => [],
            'orders' => []
        ];

        foreach ($monthlySales as $data) {
            $monthName = Carbon::createFromDate($data->year, $data->month, 1)->format('M Y');
            $chartData['labels'][] = $monthName;
            $chartData['orders'][] = $data->total_orders;
        }

        // Get top 10 best-selling products
        $bestSellers = TransactionSalesDetails::select(
            'item_id',
            DB::raw('SUM(qty) as total_sold'),
            DB::raw('COUNT(DISTINCT transaction_sales_id) as total_transactions')
        )
        ->groupBy('item_id')
        ->orderBy('total_sold', 'desc')
        ->limit(10)
        ->with(['masterItem.itemDetails' => function($query) {
            // Get price for regular customer (customer_type_id = 1)
            $query->where('customer_type_id', 1);
        }])
        ->get()
        ->map(function ($item) {
            // Add image path based on product name
            $productName = strtolower($item->masterItem->name_item ?? '');
            $productName = str_replace([' ', '_', '/', '\\', '(', ')', '.', ','], '-', $productName);
            $productName = preg_replace('/-+/', '-', $productName); // Remove multiple dashes
            $productName = trim($productName, '-'); // Remove leading/trailing dashes
            
            // Available images in folder
            $availableImages = [
                'abon-hati-ayam',
                'beef-pudding',
                'chicken-pudding',
                'ciki-bone-broth',
                'full-meal-bolognese',
                'full-meal-dori-bumbu-kuning',
                'full-meal-hati-ayam-bumbu-kuning',
                'full-meal-nasi-uduk-ayam-telor',
                'full-meal-opor-otak',
                'hati-ayam-lengkuas'
            ];
            
            // Check if exact match exists
            if (in_array($productName, $availableImages)) {
                $item->image_url = asset('images/nyam/' . $productName . '.jpg');
            } else {
                // Try partial match
                $found = false;
                foreach ($availableImages as $imageName) {
                    if (str_contains($productName, $imageName) || str_contains($imageName, $productName)) {
                        $item->image_url = asset('images/nyam/' . $imageName . '.jpg');
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $item->image_url = asset('images/nyam.png');
                }
            }
            
            // Get sell price from master_items_details (customer_type_id = 1 for regular customers)
            $item->sell_price = $item->masterItem->getSellPrice(1);
            
            return $item;
        });

        // Get high-rated reviews (rating 4 and 5)
        $reviews = Review::where('rating', '>=', 4)
            ->with(['masterCustomer', 'transactionSales'])
            ->latest()
            ->limit(10)
            ->get();

        return view('nyam-landing', compact('chartData', 'bestSellers', 'reviews'));
    }

    public function nyam()
    {
        // Get monthly sales data for the last 6 months
        $monthlySales = TransactionSales::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('MONTH(date) as month'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_sales')
        )
        ->where('date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Format data for chart
        $chartData = [
            'labels' => [],
            'orders' => []
        ];

        foreach ($monthlySales as $data) {
            $monthName = Carbon::createFromDate($data->year, $data->month, 1)->format('M Y');
            $chartData['labels'][] = $monthName;
            $chartData['orders'][] = $data->total_orders;
        }

        // Get top 10 best-selling products
        $bestSellers = TransactionSalesDetails::select(
            'item_id',
            DB::raw('SUM(qty) as total_sold'),
            DB::raw('COUNT(DISTINCT transaction_sales_id) as total_transactions')
        )
        ->groupBy('item_id')
        ->orderBy('total_sold', 'desc')
        ->limit(10)
        ->with('masterItem')
        ->get()
        ->map(function ($item) {
            // Add image path based on product name
            $productName = strtolower($item->masterItem->name_item ?? '');
            $productName = str_replace([' ', '_', '/', '\\', '(', ')', '.', ','], '-', $productName);
            $productName = preg_replace('/-+/', '-', $productName);
            $productName = trim($productName, '-');
            
            // Available images in folder
            $availableImages = [
                'abon-hati-ayam',
                'beef-pudding',
                'chicken-pudding',
                'ciki-bone-broth',
                'full-meal-bolognese',
                'full-meal-dori-bumbu-kuning',
                'full-meal-hati-ayam-bumbu-kuning',
                'full-meal-nasi-uduk-ayam-telor',
                'full-meal-opor-otak',
                'hati-ayam-lengkuas'
            ];
            
            // Check if exact match exists
            if (in_array($productName, $availableImages)) {
                $item->image_url = asset('images/nyam/' . $productName . '.jpg');
            } else {
                // Try partial match
                $found = false;
                foreach ($availableImages as $imageName) {
                    if (str_contains($productName, $imageName) || str_contains($imageName, $productName)) {
                        $item->image_url = asset('images/nyam/' . $imageName . '.jpg');
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $item->image_url = asset('images/nyam.png');
                }
            }
            
            return $item;
        });

        // Get high-rated reviews (rating 4 and 5)
        $reviews = Review::where('rating', '>=', 4)
            ->with(['masterCustomer', 'transactionSales'])
            ->latest()
            ->limit(10)
            ->get();

        return view('nyam-landing', compact('chartData', 'bestSellers', 'reviews'));
    }

    public function gentleLiving()
    {
        // Get monthly sales data for last 6 months
        $monthlySales = TransactionSales::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('MONTH(date) as month'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_sales')
        )
        ->where('date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Format data for chart
        $chartData = [
            'labels' => [],
            'orders' => []
        ];

        foreach ($monthlySales as $data) {
            $monthName = Carbon::createFromDate($data->year, $data->month, 1)->format('M Y');
            $chartData['labels'][] = $monthName;
            $chartData['orders'][] = $data->total_orders;
        }

        return view('gentleLiving-landing', compact('chartData'));
    }

        public function mamina()
    {
        // Static chart data showing sales growth from 7M to 10M
        $chartData = [
            'labels' => ['Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025', 'Jun 2025', 'Jul 2025', 'Agt 2025', 'Sep 2025', 'Okt 2025', 'Nov 2025', 'Des 2025'],
            'sales' => [7000000, 7200000, 7500000, 7800000, 8100000, 8400000, 8700000, 9000000, 9300000, 9600000, 9800000, 10000000]
        ];

        return view('Mamina-landing', compact('chartData'));
    }
}
