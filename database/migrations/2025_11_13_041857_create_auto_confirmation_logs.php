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
        Schema::create('auto_confirmation_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('Reference ke orders.id');
            $table->string('order_number')->comment('Order number untuk reference');
            $table->enum('action', ['shipped', 'auto_confirmed', 'manual_confirmed', 'cancelled']); 
            $table->timestamp('shipped_at')->nullable()->comment('Kapan pesanan dikirim');
            $table->timestamp('action_at')->comment('Kapan action dilakukan');
            $table->integer('days_elapsed')->nullable()->comment('Hari yang berlalu sejak shipped');
            $table->text('notes')->nullable()->comment('Catatan untuk action');
            $table->json('order_snapshot')->nullable()->comment('Snapshot data order saat action');
            $table->integer('performed_by')->nullable()->comment('User ID yang melakukan action');
            $table->string('ip_address')->nullable()->comment('IP address saat action');
            $table->string('user_agent')->nullable()->comment('User agent saat action');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('order_id');
            $table->index('order_number');
            $table->index('action');
            $table->index('action_at');
            $table->index('performed_by');
            $table->index(['action', 'action_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_confirmation_logs');
    }
};
