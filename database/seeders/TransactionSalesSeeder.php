<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransactionSales;
use App\Models\TransactionPayment;
use Illuminate\Support\Facades\DB;

class TransactionSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing transactions
        DB::table('transaction_payments')->delete();
        DB::table('transaction_sales_details')->delete();
        DB::table('transaction_sales')->delete();

        // Get some master items for testing
        $items = DB::table('master_items')->limit(3)->get();
        $customers = DB::table('master_customers')->limit(5)->get();
        
        if ($items->isEmpty()) {
            echo "No master items found. Please seed master_items first.\n";
            return;
        }
        
        if ($customers->isEmpty()) {
            echo "No master customers found. Please seed master_customers first.\n";
            return;
        }
        
        echo "Creating transactions with customers: \n";
        foreach ($customers as $customer) {
            echo "- Customer ID {$customer->customer_id}: {$customer->name_customer}\n";
        }

        // Create transactions for each customer
        foreach ($customers as $index => $customer) {
            $transactionNumber = 'TXN-' . date('Ymd') . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
            $couriers = ['JNE', 'TIKI', 'POS'];
            $services = ['REG', 'YES', 'OKE'];
            
            $transaction = TransactionSales::create([
                'number' => $transactionNumber,
                'branch_id' => 1,
                'user_id' => 1,
                'customer_id' => $customer->customer_id,
                'sales_type_id' => 1,
                'expedition_id' => rand(1, 3),
                'date' => now()->subDays(rand(1, 30)),
                'subtotal' => rand(75000, 250000),
                'discount_amount' => 0,
                'discount_percentage' => 0,
                'total_amount' => rand(85000, 275000),
                'shipping_cost' => rand(10000, 25000),
                'shipping_courier' => $couriers[array_rand($couriers)],
                'shipping_service' => $services[array_rand($services)],
                'shipping_etd' => rand(1, 5) . '-' . rand(2, 7) . ' hari',
                'tracking_number' => ($index % 2 == 0) ? 'TRK' . rand(1000000000, 9999999999) : null,
                'shipping_status' => $statuses[array_rand($statuses)],
                'shipping_notes' => ($index % 3 == 0) ? 'Barang sudah siap dikirim' : null,
                'whatsapp' => $customer->phone_customer,
                'shipping_address' => $customer->address_customer,
                'notes' => 'Order untuk customer: ' . $customer->name_customer
            ]);
            
            echo "Created transaction {$transactionNumber} for customer: {$customer->name_customer}\n";
            
            // Create transaction details
            $numItems = rand(1, 3);
            for ($j = 0; $j < $numItems; $j++) {
                $item = $items[array_rand($items->toArray())];
                $qty = rand(1, 5);
                $sellPrice = rand(25000, 100000);
                $subtotal = $sellPrice * $qty;

                DB::table('transaction_sales_details')->insert([
                    'transaction_sales_id' => $transaction->transaction_sales_id,
                    'item_id' => $item->item_id,
                    'qty' => $qty,
                    'costprice' => $sellPrice * 0.7, // 70% of sell price
                    'sell_price' => $sellPrice,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'discount_percentage' => 0,
                    'total_amount' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        echo "Transaction sales seeded successfully! Created " . $customers->count() . " transactions.\n";
    }
}
