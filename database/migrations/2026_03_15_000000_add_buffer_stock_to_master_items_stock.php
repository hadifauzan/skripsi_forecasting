<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_items_stock', function (Blueprint $table) {
            $table->integer('buffer_stock')->default(0)->after('stock')->comment('Buffer stock untuk safety stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_items_stock', function (Blueprint $table) {
            $table->dropColumn('buffer_stock');
        });
    }
};
