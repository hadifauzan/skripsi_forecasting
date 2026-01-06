<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini menambahkan kategori khusus untuk artikel:
     * - Kesehatan Bayi
     * - Nutrisi
     * - Parenting
     * - Balita
     */
    public function run(): void
    {
        $articleCategories = [
            ['name_category' => 'Kesehatan Bayi'],
            ['name_category' => 'Nutrisi'],
            ['name_category' => 'Parenting'],
            ['name_category' => 'Balita'],
        ];

        foreach ($articleCategories as $category) {
            // Check if category already exists
            $exists = DB::table('master_categories')
                ->where('name_category', $category['name_category'])
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                DB::table('master_categories')->insert([
                    'name_category' => $category['name_category'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                
                $this->command->info("✓ Kategori '{$category['name_category']}' berhasil ditambahkan");
            } else {
                $this->command->info("⚠ Kategori '{$category['name_category']}' sudah ada, skip...");
            }
        }

        $this->command->info("\n✅ Seeder kategori artikel selesai!");
    }
}
