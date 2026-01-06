<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterInventory;

class MasterInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Master Inventories...');

        // Ambil branch IDs dari master_branches
        $branches = DB::table('master_branches')->get();

        if ($branches->isEmpty()) {
            $this->command->error('No branches found! Please run MasterBranchSeeder first.');
            return;
        }

        foreach ($branches as $branch) {
            // Data inventory untuk setiap branch (simplified to match table structure)
            $inventories = [
                [
                    'branch_id' => $branch->branch_id,
                    'name_inventory' => "Gentle Living Inventory ",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($inventories as $inventory) {
                // Cek apakah inventory sudah ada berdasarkan nama dan branch
                $existingInventory = DB::table('master_inventories')
                    ->where('name_inventory', $inventory['name_inventory'])
                    ->where('branch_id', $inventory['branch_id'])
                    ->first();

                if (!$existingInventory) {
                    DB::table('master_inventories')->insert($inventory);
                    $this->command->info("✓ Created inventory: {$inventory['name_inventory']}");
                } else {
                    $this->command->info("- Inventory already exists: {$inventory['name_inventory']}");
                }
            }
        }

        $totalInventories = DB::table('master_inventories')->count();
        $this->command->info("Master Inventories seeding completed! Total inventories: {$totalInventories}");
    }
}
