<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get orders that need payments (not cancelled)
        $orders = Order::whereNotIn('status', ['cancelled'])->get();
        
        if ($orders->isEmpty()) {
            $this->command->warn('No orders found. Please run OrderSeeder first.');
            return;
        }

        $paymentCount = 0;
        
        foreach ($orders as $order) {
            // Skip if payment already exists for this order
            if (Payment::where('order_id', $order->id)->exists()) {
                continue;
            }
            
            // Determine payment status based on order status
            $transactionStatus = $this->getTransactionStatusByOrderStatus($order->status);
            $paymentType = $this->getRandomPaymentType();
            
            // Skip creating payment for some pending orders
            if ($order->status === 'pending' && rand(1, 100) > 60) {
                continue;
            }
            
            $paymentDate = $this->getPaymentDate($order);
            $transactionId = $this->generateTransactionId($paymentType);
            
            Payment::create([
                'order_id' => $order->id, // Use order ID instead of order number
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'gross_amount' => $order->total_amount,
                'transaction_status' => $transactionStatus,
                'midtrans_response' => $this->generateMidtransResponse($transactionId, $transactionStatus, $order),
                'qr_code_url' => $paymentType === 'qris' ? $this->generateQRCodeUrl($transactionId) : null,
                'expired_at' => $paymentDate->copy()->addHours(24), // 24 hours expiry
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'created_at' => $paymentDate,
                'updated_at' => $transactionStatus === 'settlement' ? $paymentDate->addMinutes(rand(5, 120)) : $paymentDate,
            ]);
            
            $paymentCount++;
        }
        
        $this->command->info("Created {$paymentCount} payments successfully!");
        $this->showPaymentStatistics();
    }

    /**
     * Get transaction status based on order status
     */
    private function getTransactionStatusByOrderStatus(string $orderStatus): string
    {
        switch ($orderStatus) {
            case 'pending':
                return rand(1, 100) > 40 ? 'pending' : 'expire';
            case 'confirmed':
            case 'processing':
            case 'shipped':
            case 'delivered':
                return 'settlement';
            default:
                return 'pending';
        }
    }

    /**
     * Get random payment type
     */
    private function getRandomPaymentType(): string
    {
        $types = [
            'bank_transfer' => 40,
            'qris' => 25,
            'credit_card' => 15,
            'gopay' => 12,
            'shopeepay' => 8
        ];
        
        return $this->weightedRandom($types);
    }

    /**
     * Weighted random selection
     */
    private function weightedRandom(array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($weights as $value => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $value;
            }
        }
        
        return array_key_first($weights);
    }

    /**
     * Get payment date based on order
     */
    private function getPaymentDate(Order $order): Carbon
    {
        $orderDate = Carbon::parse($order->created_at);
        
        switch ($order->status) {
            case 'pending':
                // Payment attempt within 1-3 days of order
                return $orderDate->copy()->addHours(rand(1, 72));
            case 'confirmed':
            case 'processing':
            case 'shipped':
            case 'delivered':
                // Payment made within few hours of order
                return $orderDate->copy()->addMinutes(rand(30, 480)); // 30 min to 8 hours
            default:
                return $orderDate->copy()->addHours(rand(1, 24));
        }
    }

    /**
     * Generate transaction ID based on payment type
     */
    private function generateTransactionId(string $type): string
    {
        $prefix = strtoupper(substr($type, 0, 3));
        return $prefix . '-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    /**
     * Generate Midtrans response JSON
     */
    private function generateMidtransResponse(string $transactionId, string $status, Order $order): array
    {
        $baseResponse = [
            'status_code' => $status === 'settlement' ? '200' : ($status === 'pending' ? '201' : '202'),
            'status_message' => $status === 'settlement' ? 'Success, transaction is found' : 'Pending transaction',
            'transaction_id' => $transactionId,
            'order_id' => $order->order_number,
            'merchant_id' => 'G123456789',
            'gross_amount' => (string) $order->total_amount,
            'currency' => 'IDR',
            'payment_type' => $this->getPaymentTypeForMidtrans($order),
            'transaction_time' => now()->toISOString(),
            'transaction_status' => $status,
            'fraud_status' => 'accept',
        ];

        return $baseResponse;
    }

    /**
     * Get payment type for Midtrans format
     */
    private function getPaymentTypeForMidtrans(Order $order): string
    {
        $types = ['bank_transfer', 'qris', 'credit_card', 'gopay', 'shopeepay'];
        return $types[array_rand($types)];
    }

    /**
     * Generate QR Code URL for QRIS payments
     */
    private function generateQRCodeUrl(string $transactionId): string
    {
        return "https://api.sandbox.midtrans.com/v2/qris/{$transactionId}/qr-code";
    }

    /**
     * Show payment statistics
     */
    private function showPaymentStatistics(): void
    {
        $totalPayments = Payment::count();
        $totalSettled = Payment::where('transaction_status', 'settlement')->sum('gross_amount');
        
        $statusDistribution = Payment::selectRaw('transaction_status, COUNT(*) as count, SUM(gross_amount) as total')
            ->groupBy('transaction_status')
            ->orderBy('count', 'desc')
            ->get();

        $typeDistribution = Payment::selectRaw('payment_type, COUNT(*) as count')
            ->groupBy('payment_type')
            ->orderBy('count', 'desc')
            ->get();

        $this->command->info("");
        $this->command->info("=== PAYMENT STATISTICS ===");
        $this->command->info("Total Payments: {$totalPayments}");
        $this->command->info("Total Settled Revenue: Rp " . number_format($totalSettled, 0, ',', '.'));
        $this->command->info("");
        
        $this->command->info("Transaction Status Distribution:");
        foreach ($statusDistribution as $stat) {
            $percentage = round(($stat->count / $totalPayments) * 100, 1);
            $status = ucfirst($stat->transaction_status);
            $amount = number_format($stat->total, 0, ',', '.');
            $this->command->info("  {$status}: {$stat->count} payments ({$percentage}%) - Rp {$amount}");
        }

        $this->command->info("");
        $this->command->info("Payment Type Distribution:");
        foreach ($typeDistribution as $stat) {
            $percentage = round(($stat->count / $totalPayments) * 100, 1);
            $type = ucwords(str_replace('_', ' ', $stat->payment_type));
            $this->command->info("  {$type}: {$stat->count} payments ({$percentage}%)");
        }
    }
}