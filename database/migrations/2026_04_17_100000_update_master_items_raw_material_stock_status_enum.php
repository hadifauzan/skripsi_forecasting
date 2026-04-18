<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE master_items_raw_material MODIFY stock_status ENUM('normal','low','critical','overstock','out_of_stock') DEFAULT 'normal'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('master_items_raw_material')
            ->where('stock_status', 'out_of_stock')
            ->update(['stock_status' => 'critical']);

        DB::statement("ALTER TABLE master_items_raw_material MODIFY stock_status ENUM('normal','low','critical','overstock') DEFAULT 'normal'");
    }
};
