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
        Schema::create('arima_forecast_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('produk')->unique();
            $table->string('arima_order', 50);
            $table->decimal('mae', 12, 4)->default(0);
            $table->decimal('rmse', 12, 4)->default(0);
            $table->decimal('mape_percentage', 12, 4)->default(0);
            $table->boolean('stationary')->nullable();
            $table->decimal('adf_p_value', 12, 6)->nullable();
            $table->string('kategori_mae', 20)->index();
            $table->timestamps();
        });

        Schema::create('arima_forecast_mae_category_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_mae', 20)->unique();
            $table->unsignedInteger('jumlah_produk')->default(0);
            $table->decimal('mae_rata_rata', 12, 4)->default(0);
            $table->decimal('rmse_rata_rata', 12, 4)->default(0);
            $table->decimal('mape_rata_rata', 12, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arima_forecast_mae_category_summaries');
        Schema::dropIfExists('arima_forecast_summaries');
    }
};
