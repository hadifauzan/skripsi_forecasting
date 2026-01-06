<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\LandingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of active orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.masterItem', 'user'])
                     ->whereNotIn('status', ['delivered', 'cancelled']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics from all active orders (not just current page)
        $stats = [
            'pending' => Order::whereNotIn('status', ['delivered', 'cancelled'])
                              ->where('status', 'pending')->count(),
            'processing' => Order::whereNotIn('status', ['delivered', 'cancelled'])
                                 ->where('status', 'processing')->count(),
            'shipped' => Order::whereNotIn('status', ['delivered', 'cancelled'])
                              ->where('status', 'shipped')->count(),
            'total' => Order::whereNotIn('status', ['delivered', 'cancelled'])->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.masterItem', 'user'])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'shipping_status' => 'nullable|string',
            'shipping_notes' => 'nullable|string',
            'tracking_number' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            
            // Update order fields
            $updateData = [
                'status' => $request->status,
            ];
            
            if ($request->filled('shipping_status')) {
                $updateData['shipping_status'] = $request->shipping_status;
            }
            
            if ($request->filled('shipping_notes')) {
                $updateData['shipping_notes'] = $request->shipping_notes;
            }
            
            if ($request->filled('tracking_number')) {
                $updateData['tracking_number'] = $request->tracking_number;
            }

            $order->update($updateData);

            // If order is completed, move to transaction_sales
            if (in_array($request->status, ['delivered', 'cancelled'])) {
                $landingController = new LandingController();
                $landingController->moveOrderToTransactionSales($id);
                
                $message = 'Order status updated and moved to transaction history';
            } else {
                $message = 'Order status updated successfully';
            }

            DB::commit();
            
            Log::info('Admin updated order status', [
                'order_id' => $id,
                'new_status' => $request->status,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update order status', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Gagal mengupdate status pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Get order statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'total_active' => Order::whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk update order status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $orderIds = $request->order_ids;
            $status = $request->status;
            
            foreach ($orderIds as $orderId) {
                $order = Order::findOrFail($orderId);
                $order->update(['status' => $status]);

                // If completing orders, move to transaction_sales
                if (in_array($status, ['delivered', 'cancelled'])) {
                    $landingController = new LandingController();
                    $landingController->moveOrderToTransactionSales($orderId);
                }
            }

            DB::commit();
            
            $count = count($orderIds);
            return redirect()->back()->with('success', "Successfully updated {$count} orders to {$status}");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to bulk update orders: ' . $e->getMessage());
        }
    }
}
