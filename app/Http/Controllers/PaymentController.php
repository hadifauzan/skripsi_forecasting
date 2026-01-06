<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Create QRIS payment
     */
    public function createQrisPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'items' => 'nullable|array'
        ]);

        $orderData = [
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'items' => $request->items ?? []
        ];

        $result = $this->midtrans->createQrisPayment($orderData);

        if ($result['success']) {
            // Save payment to database using data from Midtrans response
            $midtransData = $result['data'];
            $orderId = $midtransData->transaction_details->order_id ?? $request->order_id;
            $grossAmount = $midtransData->transaction_details->gross_amount ?? $request->amount;
            
            Payment::create([
                'order_id' => $orderId, // Use order ID from Midtrans response
                'transaction_id' => $midtransData->transaction_id ?? null,
                'payment_type' => 'qris',
                'gross_amount' => $grossAmount, // Use gross amount from Midtrans response
                'transaction_status' => 'pending',
                'midtrans_response' => $result['data'],
                'qr_code_url' => $result['qr_code_url'],
                'expired_at' => now()->addMinutes(10),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $orderId, // Return order ID from Midtrans
                'qr_code_url' => $result['qr_code_url'],
                'expired_at' => now()->addMinutes(10)->toISOString(),
                'gross_amount' => $grossAmount // Include gross amount in response
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }
    }

    /**
     * Handle Midtrans callback
     */
    public function handleCallback(Request $request)
    {
        $callback = $this->midtrans->handleCallback($request->all());

        if (isset($callback['order_id'])) {
            $payment = Payment::where('order_id', $callback['order_id'])->first();
            
            if ($payment) {
                $payment->update([
                    'transaction_status' => $callback['status'] ?? $callback['transaction_status'],
                    'midtrans_response' => array_merge($payment->midtrans_response ?? [], $callback)
                ]);

                // Update order status if exists
                $order = Order::where('order_number', $callback['order_id'])->first();
                if ($order) {
                    if ($callback['status'] === 'success') {
                        $order->update(['status' => 'confirmed']);
                    } elseif (in_array($callback['status'], ['failed', 'cancelled', 'expired'])) {
                        $order->update(['status' => 'cancelled']);
                    }
                }
            }
        }

        return response('OK', 200);
    }

    /**
     * Check payment status
     */
    public function checkStatus($orderId)
    {
        $payment = Payment::where('order_id', $orderId)->first();
        
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Check status from Midtrans
        $result = $this->midtrans->getTransactionStatus($orderId);
        
        if ($result['success']) {
            $status = $result['data'];
            $payment->update([
                'transaction_status' => $status->transaction_status,
                'midtrans_response' => array_merge($payment->midtrans_response ?? [], (array)$status)
            ]);
        }

        return response()->json([
            'order_id' => $payment->order_id,
            'status' => $payment->transaction_status,
            'qr_code_url' => $payment->qr_code_url,
            'expired_at' => $payment->expired_at
        ]);
    }

    /**
     * Show payment page
     */
    /**
     * Show QRIS payment page
     */
    public function showQrisPayment($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        return view('payment.qris-show', compact('payment'));
    }

    /**
     * Show payment page (legacy method)
     */
    public function showPayment($orderId)
    {
        $payment = Payment::where('order_id', $orderId)->first();
        
        if (!$payment) {
            abort(404, 'Payment not found');
        }

        return view('payment.qris', compact('payment'));
    }
}
