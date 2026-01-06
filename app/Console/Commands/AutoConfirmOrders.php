<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\AutoConfirmationLog;
use Illuminate\Support\Facades\Log;

class AutoConfirmOrders extends Command
{
    protected $signature = 'orders:auto-confirm';
    protected $description = 'Auto confirm orders that have been shipped for specified days without customer confirmation';

    public function handle()
    {
        if (!config('services.auto_confirmation.enabled', false)) {
            $this->info('Auto confirmation is disabled in configuration');
            return;
        }

        $this->info('Starting auto confirmation process...');

        $days = config('services.auto_confirmation.days', 2);
        
        // Get orders that need auto confirmation
        $ordersToConfirm = Order::where('status', 'shipped')
            ->where('auto_confirmed', false)
            ->whereNotNull('shipped_at')
            ->whereNull('delivered_at')
            ->where('shipped_at', '<=', now()->subDays($days))
            ->get();

        if ($ordersToConfirm->isEmpty()) {
            $this->info('No orders need auto confirmation.');
            return;
        }

        $confirmedCount = 0;
        $failedCount = 0;

        foreach ($ordersToConfirm as $order) {
            try {
                $daysElapsed = $order->shipped_at->diffInDays(now());
                
                $order->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                    'confirmed_at' => now(),
                    'auto_confirmed' => true,
                    'confirmation_type' => 'auto',
                    'auto_confirmation_notes' => "Pesanan dikonfirmasi otomatis setelah {$daysElapsed} hari pengiriman tanpa konfirmasi manual dari pembeli."
                ]);

                // Log the auto confirmation
                AutoConfirmationLog::logAction(
                    $order, 
                    'auto_confirmed', 
                    null, 
                    "Auto confirmed after {$daysElapsed} days"
                );

                $confirmedCount++;
                $this->line("✓ Order {$order->order_number} auto-confirmed after {$daysElapsed} days");
                
                Log::info("Order auto-confirmed", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->customer_email,
                    'shipped_at' => $order->shipped_at,
                    'confirmed_at' => $order->confirmed_at,
                    'days_elapsed' => $daysElapsed
                ]);

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to auto-confirm order {$order->order_number}: {$e->getMessage()}");
                
                Log::error("Auto confirmation failed", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Auto confirmation completed!");
        $this->info("✓ Confirmed: {$confirmedCount} orders");
        
        if ($failedCount > 0) {
            $this->warn("✗ Failed: {$failedCount} orders");
        }

        return 0;
    }
}
