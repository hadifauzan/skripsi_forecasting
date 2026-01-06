<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterBranch;

class MasterBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Master Branches...');

        // Cek apakah sudah ada company dengan ID 1
        $companyId = DB::table('master_companies')->first()->company_id ?? 1;

        // Data branches untuk Gentle Living
        $branches = [
            [
                'company_id' => $companyId,
                'name_branch' => 'Gentle Living Pusat',
                'phone_branch' => '0821-3716-1033',
                'address_branch' => 'Jl. Pandanwangi Park No 58 Pandanwangi, Kec. Blimbing, Kota Malang, Jawa Timur 65126',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($branches as $branch) {
            // Cek apakah branch sudah ada berdasarkan nama
            $existingBranch = DB::table('master_branches')
                ->where('name_branch', $branch['name_branch'])
                ->where('company_id', $branch['company_id'])
                ->first();

            if (!$existingBranch) {
                DB::table('master_branches')->insert($branch);
                $this->command->info("✓ Created branch: {$branch['name_branch']}");
            } else {
                $this->command->info("- Branch already exists: {$branch['name_branch']}");
            }
        }

        $totalBranches = DB::table('master_branches')->count();
        $this->command->info("Master Branches seeding completed! Total branches: {$totalBranches}");
    }
}
