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
        Schema::create('buffer_stock', function (Blueprint $table) {
            $table->id();
            
            // Nama produk (unique identifier)
            $table->string('produk')->unique();
            
            // Statistik pemakaian harian
            $table->decimal('max_daily_sales', 10, 2);           // Pemakaian maksimum
            $table->decimal('avg_daily_sales', 10, 2);           // Pemakaian rata-rata
            $table->decimal('standar_deviasi', 10, 2);           // Standard deviation
            
            // Buffer Stock dan Safety Stock
            $table->decimal('buffer_stock_unit', 10, 2);         // Buffer stock dalam unit
            $table->decimal('safety_stock_95percent_unit', 10, 2); // Safety stock dengan service level 95%
            
            // Reorder Point
            $table->decimal('rop_unit', 10, 2);                  // Reorder Point dalam unit
            
            // Lead time information
            $table->decimal('avg_lead_time_hari', 5, 1);         // Rata-rata lead time
            $table->decimal('max_lead_time_hari', 5, 1);         // Lead time maksimum
            
            // Formula information
            $table->text('rumus_buffer_stock')->nullable();    // Formula untuk buffer stock
            $table->text('rumus_rop')->nullable();              // Formula untuk ROP
            
            // Timestamps
            $table->timestamps();
            
            // Index untuk query yang lebih cepat
            $table->index('produk');
            $table->index('buffer_stock_unit');
            $table->index('rop_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffer_stock');
    }
};
