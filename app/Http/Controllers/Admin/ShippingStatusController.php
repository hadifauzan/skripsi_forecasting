<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ShippingStatusController extends Controller
{
    /**
     * Display a listing of orders with shipping status
     */
    public function index(Request $request)
    {
        // Build query with proper joins and relationships
        $query = TransactionSales::query()
            ->select('transaction_sales.*')
            ->with([
                'customer:customer_id,name_customer,phone_customer,address_customer', 
                'transactionSalesDetails:transaction_sales_detail_id,transaction_sales_id,item_id,qty,sell_price,total_amount',
                'transactionSalesDetails.masterItem:item_id,name_item,picture_item'
            ])
            ->leftJoin('master_customers', 'transaction_sales.customer_id', '=', 'master_customers.customer_id')
            ->orderBy('transaction_sales.created_at', 'desc');

        // Filter by shipping status if provided
        if ($request->filled('status_filter')) {
            $query->where('transaction_sales.shipping_status', $request->status_filter);
        }

        // Enhanced search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_sales.number', 'LIKE', "%{$search}%")
                  ->orWhere('transaction_sales.tracking_number', 'LIKE', "%{$search}%")
                  ->orWhere('master_customers.name_customer', 'LIKE', "%{$search}%")
                  ->orWhere('master_customers.phone_customer', 'LIKE', "%{$search}%");
            });
        }

        // Add debug information if needed
        if ($request->has('debug')) {
            $debugQuery = $query->toSql();
            Log::info('Shipping Status Query:', ['sql' => $debugQuery, 'bindings' => $query->getBindings()]);
        }

        $transactions = $query->paginate(15);

        // Debug: Log relationship loading for first transaction
        if ($transactions->isNotEmpty() && $request->has('debug')) {
            $firstTransaction = $transactions->first();
            Log::info('First Transaction Debug:', [
                'id' => $firstTransaction->transaction_sales_id,
                'customer_id' => $firstTransaction->customer_id,
                'customer_loaded' => $firstTransaction->relationLoaded('customer'),
                'customer_exists' => $firstTransaction->customer ? true : false,
                'customer_name' => $firstTransaction->customer ? $firstTransaction->customer->name_customer : null
            ]);
        }

        return view('admin.shipping-status.index', compact('transactions'));
    }

    /**
     * Update shipping status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'shipping_status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $transaction = TransactionSales::findOrFail($id);
            
            $oldStatus = $transaction->shipping_status;
            
            $transaction->update([
                'shipping_status' => $request->shipping_status,
                'tracking_number' => $request->tracking_number,
                'shipping_notes' => $request->notes,
                'updated_at' => now()
            ]);

            Log::info("Shipping status updated", [
                'transaction_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->shipping_status,
                'admin_id' => Auth::user() ? Auth::user()->user_id : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pengiriman berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to update shipping status: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status pengiriman.'
            ], 500);
        }
    }

    /**
     * Debug method to check data integrity
     */
    public function debug(Request $request)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        // Check transactions with customer data
        $transactions = TransactionSales::with('customer')
            ->limit(5)
            ->get();

        $debugData = [
            'total_transactions' => TransactionSales::count(),
            'transactions_with_customer' => TransactionSales::whereNotNull('customer_id')->count(),
            'total_customers' => \App\Models\MasterCustomers::count(),
        ];

        foreach ($transactions as $transaction) {
            $debugData['sample_transactions'][] = [
                'id' => $transaction->transaction_sales_id,
                'number' => $transaction->number,
                'customer_id' => $transaction->customer_id,
                'customer_exists' => $transaction->customer ? 'Yes' : 'No',
                'customer_name' => $transaction->customer ? $transaction->customer->name_customer : 'NULL'
            ];
        }

        return response()->json($debugData);
    }

    /**
     * Get transaction details for modal
     */
    public function show($id)
    {
        try {
            // Enhanced query with explicit relationship loading
            $transaction = TransactionSales::query()
                ->with([
                    'customer:customer_id,name_customer,phone_customer,address_customer,location_notes',
                    'transactionSalesDetails:transaction_sales_detail_id,transaction_sales_id,item_id,qty,sell_price,total_amount',
                    'transactionSalesDetails.masterItem:item_id,name_item,picture_item,description_item'
                ])
                ->leftJoin('master_customers', 'transaction_sales.customer_id', '=', 'master_customers.customer_id')
                ->select('transaction_sales.*', 'master_customers.name_customer as customer_name')
                ->where('transaction_sales.transaction_sales_id', $id)
                ->first();

            if (!$transaction) {
                Log::warning("Transaction not found", ['id' => $id]);
                return response('<div class="text-red-600 text-center">Transaksi tidak ditemukan untuk ID: ' . $id . '</div>', 404);
            }

            // Debug logging
            Log::info('Transaction Show Debug:', [
                'transaction_id' => $transaction->transaction_sales_id,
                'customer_id' => $transaction->customer_id,
                'customer_loaded' => $transaction->relationLoaded('customer'),
                'customer_name' => $transaction->customer ? $transaction->customer->name_customer : 'NULL',
                'details_count' => $transaction->transactionSalesDetails ? $transaction->transactionSalesDetails->count() : 0
            ]);

            // Check if this is update mode
            $isUpdateMode = request()->query('mode') === 'update';

            return view('admin.shipping-status.show', compact('transaction', 'isUpdateMode'));

        } catch (\Exception $e) {
            Log::error("Error finding transaction: " . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response('<div class="text-red-600 text-center">Terjadi kesalahan: ' . $e->getMessage() . '</div>', 500);
        }
    }
}