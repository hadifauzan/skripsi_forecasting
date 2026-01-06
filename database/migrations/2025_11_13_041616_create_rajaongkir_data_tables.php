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
        // Tabel untuk menyimpan data provinsi dari RajaOngkir
        Schema::create('rajaongkir_provinces', function (Blueprint $table) {
            $table->id('province_id'); // Sesuai dengan ID dari RajaOngkir
            $table->string('province_name');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('province_name');
            $table->unique('province_id');
        });

        // Tabel untuk menyimpan data kota dari RajaOngkir
        Schema::create('rajaongkir_cities', function (Blueprint $table) {
            $table->id(); 
            $table->string('city_id')->unique(); // ID dari RajaOngkir
            $table->integer('province_id'); // Reference ke province_id (tanpa foreign key)
            $table->string('type'); // Kota/Kabupaten
            $table->string('city_name');
            $table->string('postal_code');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('province_id');
            $table->index('city_name');
            $table->index('city_id');
            $table->index('postal_code');
        });

        // Tabel untuk caching hasil perhitungan ongkir
        Schema::create('shipping_costs_cache', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city_id')->comment('ID kota asal dari RajaOngkir');
            $table->string('destination_city_id')->comment('ID kota tujuan dari RajaOngkir');
            $table->integer('weight')->comment('Berat dalam gram');
            $table->string('courier')->comment('Kode kurir: jne, jnt, spx, dll');
            $table->string('service')->comment('Layanan: REG, YES, OKE, dll');
            $table->decimal('cost', 10, 2)->comment('Biaya kirim');
            $table->string('etd')->nullable()->comment('Estimasi waktu kirim');
            $table->text('service_description')->nullable();
            $table->timestamp('cached_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Composite index untuk query cepat dengan nama yang lebih pendek
            $table->index(['origin_city_id', 'destination_city_id', 'weight', 'courier'], 'shipping_cache_lookup_idx');
            $table->index('expires_at', 'shipping_cache_expires_idx');
            $table->index(['courier', 'service'], 'shipping_cache_courier_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_costs_cache');
        Schema::dropIfExists('rajaongkir_cities');
        Schema::dropIfExists('rajaongkir_provinces');
    }
};
