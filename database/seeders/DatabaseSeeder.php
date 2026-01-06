<?php

namespace Database\Seeders;

use App\Models\TransactionSales;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MasterCompanySeeder::class,
            MasterCategorySeeder::class,
            MasterCategoryArticleSeeder::class, // Add master category articles
            ArticleCategorySeeder::class,      // Add article categories after master categories
            MasterBranchSeeder::class,         // New: Add branches after company
            MasterInventorySeeder::class,      // New: Add inventories after branches
            MasterCustomerTypeSeeder::class,
            MasterCustomersSeeder::class,      // Then customers
            MasterItemSeeder::class,
            RoleSeeder::class,
            AdminRolesSeeder::class,           // New: Add admin roles after basic roles
            UserSeeder::class,
            HomepageContentSeeder::class,
            ProductContentSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
            TransactionSalesSeeder::class,
            PartnerContentSeeder::class,
            ResellerContentSeeder::class,
            ReviewSeeder::class,
            AffiliateSubmissionsSeeder::class,
            AffiliateGuideSeeder::class
        ]);
    }
}
