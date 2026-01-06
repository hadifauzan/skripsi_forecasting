<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcommerceProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products
        Product::truncate();
        
        $products = [
            [
                'name' => 'Gentle Baby Bye Bugs',
                'description' => 'Minyak untuk membantu tidur bayi menjauhkan dari gangguan serangga',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 6,
                'status' => true,
                'order' => 1,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Menjauhkan serangga', 'Menenangkan', 'Aman untuk bayi']
                ]
            ],
            [
                'name' => 'Gentle Baby Cough n Flu',
                'description' => 'Minyak untuk meredakan batuk dan flu pada bayi',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 14,
                'status' => true,
                'order' => 2,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Menghilangkan batuk dan flu', 'Mengurangi kolik', 'Aman untuk bayi']
                ]
            ],
            [
                'name' => 'Gentle Baby Deep Sleep',
                'description' => 'Minyak untuk membantu tidur bayi yang lebih nyenyak',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 18,
                'status' => true,
                'order' => 3,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Membuat bayi untuk tidur lebih nyenyak', 'Aroma segar', 'Mood booster']
                ]
            ],
            [
                'name' => 'Gentle Baby Gimme Food',
                'description' => 'Minyak untuk membantu bayi merasa kenyang dan nyaman',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 12,
                'status' => true,
                'order' => 4,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Menambahkan nafsu makan', 'Mengurangi stress', 'Aromaterapi']
                ]
            ],
            [
                'name' => 'Gentle Baby immboost',
                'description' => 'Minyak untuk meningkatkan daya tahan tubuh bayi',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 8,
                'status' => true,
                'order' => 5,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Meningkatkan ketahanan tubuh', 'Meningkatkan imunitas', 'Natural']
                ]
            ],
            [
                'name' => 'Gentle Baby Joy',
                'description' => 'Minyak untuk meningkatkan mood dan kebahagiaan bayi',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 7,
                'status' => true,
                'order' => 6,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Meningkatkan mood', 'Membuat bayi semakin bahagia', 'Aman untuk anak']
                ]
            ],
            // Additional products to reach 19 total
            [
                'name' => 'Gentle Baby LDR Booster',
                'description' => 'Membuat ASI lebih lancar',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 20,
                'status' => true,
                'order' => 7,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Minyak ASI keluar lebih banyak', 'Menenangkan ibu bayi']
                ]
            ],
            [
                'name' => 'Gentle Baby Massage Your Baby',
                'description' => 'Minyak pijat bayi kaya Vit E',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 15,
                'status' => true,
                'order' => 8,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Membantu memijat bayi', 'Antibakteri']
                ]
            ],
            [
                'name' => 'Gentle Baby Tummy Calmer',
                'description' => 'Minyak pijat perut bayi',
                'category' => 'gentle-baby',
                'price' => 38500,
                'stock' => 25,
                'status' => true,
                'order' => 9,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Aroma menyegarkan', 'Menghilangkan bau', 'Menenangkan perut bayi']
                ]
            ],
            [
                'name' => 'Twin Pack Common Cold',
                'description' => '1 paket Minyak untuk meredakan gejala flu',
                'category' => 'aromatherapy',
                'price' => 110000,
                'stock' => 10,
                'status' => true,
                'order' => 10,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Antiseptik alami', 'Antibakteri']
                ]
            ],
            [
                'name' => 'Twin Pack NewBorn',
                'description' => '1 paket Minyak untuk perawatan bayi baru lahir',
                'category' => 'gentle-baby',
                'price' => 110000,
                'stock' => 9,
                'status' => true,
                'order' => 11,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Menenangkan kulit', 'Anti-inflamasi', 'Aman untuk bayi']
                ]
            ],
            [
                'name' => 'Twin pack Travel Pack ',
                'description' => '1 paket Minyak untuk perjalanan',
                'category' => 'aromatherapy',
                'price' => 110000,
                'stock' => 22,
                'status' => true,
                'order' => 12,
                'content' => [
                    'size' => 'Standard',
                    'benefits' => ['Mood booster', 'Aroma manis', 'Energizing']
                ]
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
