<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionSales;
use App\Models\TransactionSalesDetails;
use App\Models\User;
use App\Models\MasterItem;
use App\Models\MasterCustomers;
use Illuminate\Support\Facades\DB;

class ShippedOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating orders with shipped and delivered status...\n";

        // Get existing users, customers and products
        $users = User::take(3)->get();
        $customers = MasterCustomers::take(3)->get();
        $products = MasterItem::active()->take(5)->get();

        if ($users->isEmpty()) {
            echo "No users found. Please run UserSeeder first.\n";
            return;
        }

        if ($customers->isEmpty()) {
            echo "No customers found. Please run customer seeders first.\n";
            return;
        }

        if ($products->isEmpty()) {
            echo "No active products found. Please ensure products exist.\n";
            return;
        }

        $statusOptions = ['shipped', 'delivered'];
        $courierOptions = ['JNE', 'TIKI', 'POS', 'J&T', 'SiCepat'];
        $serviceOptions = ['REG', 'YES', 'OKE', 'Express'];

        // Create 8 transactions with shipped/delivered status
        for ($i = 1; $i <= 8; $i++) {
            $user = $users->random();
            $customer = $customers->random();
            $status = $statusOptions[array_rand($statusOptions)];
            
            // Generate unique transaction number
            $transactionNumber = 'TXN-' . date('Ymd') . '-' . str_pad((TransactionSales::count() + $i), 4, '0', STR_PAD_LEFT);

            // Create transaction
            $transaction = TransactionSales::create([
                'number' => $transactionNumber,
                'branch_id' => 1,
                'user_id' => $user->user_id,
                'customer_id' => $customer->customer_id,
                'sales_type_id' => 1,
                'expedition_id' => rand(1, 5),
                'date' => now()->subDays(rand(1, 15)),
                'shipping_address' => 'Jl. Test Alamat No. ' . $i . ', Jakarta',
                'shipping_cost' => rand(15000, 35000),
                'shipping_courier' => $courierOptions[array_rand($courierOptions)],
                'shipping_service' => $serviceOptions[array_rand($serviceOptions)],
                'shipping_etd' => rand(2, 7) . ' hari',
                'tracking_number' => $status === 'shipped' || $status === 'delivered' 
                    ? 'JP' . rand(100000000, 999999999) . 'ID' 
                    : null,
                'shipping_status' => $status,
                'shipping_notes' => $status === 'shipped' 
                    ? 'Paket sedang dalam perjalanan ke alamat tujuan'
                    : 'Paket telah diterima customer',
                'whatsapp' => '08' . rand(1000000000, 9999999999),
                'notes' => 'Pesanan dari seeder - Status: ' . $status,
            ]);

            // Add 1-3 random products to each transaction
            $productCount = rand(1, 3);
            $selectedProducts = $products->random($productCount);
            $subtotal = 0;

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                $sellPrice = $product->getSellPrice(1); // Regular customer price
                $itemSubtotal = $sellPrice * $qty;
                $subtotal += $itemSubtotal;

                TransactionSalesDetails::create([
                    'transaction_sales_id' => $transaction->transaction_sales_id,
                    'item_id' => $product->item_id,
                    'qty' => $qty,
                    'costprice' => $product->costprice_item ?? 0,
                    'sell_price' => $sellPrice,
                    'subtotal' => $itemSubtotal,
                    'discount_amount' => 0,
                    'discount_percentage' => 0,
                    'total_amount' => $itemSubtotal,
                ]);
            }

            // Update transaction totals
            $transaction->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal + $transaction->shipping_cost,
            ]);

            echo "- Created transaction {$transactionNumber} with status '{$status}' for user {$user->name} (customer: {$customer->name_customer})\n";
        }

        echo "Successfully created 8 orders with shipped/delivered status!\n";
        echo "\nStatus breakdown:\n";
        echo "- Shipped orders: " . TransactionSales::where('shipping_status', 'shipped')->count() . "\n";
        echo "- Delivered orders: " . TransactionSales::where('shipping_status', 'delivered')->count() . "\n";
    }
}