<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_id' => 1,
                'name_category' => 'Gentle Baby',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name_category' => 'Healo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name_category' => 'Bundling',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name_category' => 'Full Meal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 5,
                'name_category' => 'Ice Cream',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 6,
                'name_category' => 'Snack',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 7,
                'name_category' => 'Frozen Food',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 8,
                'name_category' => 'Mamina',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
        ];

        foreach ($categories as $category) {
            DB::table('master_categories')->updateOrInsert(
                ['category_id' => $category['category_id']],
                $category
            );
        }
        
        $this->command->info('✅ Master categories seeded successfully!');
        $this->command->info('   - Product Categories (ID 1-8): Gentle Baby, Healo, Bundling, Full Meal, Ice Cream, Snack, Frozen Food, Mamina');
        $this->command->info('   - Article Categories (ID 9-12): Kesehatan Bayi, Nutrisi, Parenting, Balita');
    }
}
