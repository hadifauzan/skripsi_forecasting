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
        // Only create reviews table since orders and order_items already exist
        
        // Reviews table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('order_item_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('transaction_sales_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('rating')->nullable()->default(5)->comment('Rating 1-5');
            $table->text('comment')->nullable();
            $table->text('reply')->nullable()->comment('Admin reply to review');
            $table->timestamp('replied_at')->nullable();
            $table->integer('replied_by')->nullable()->comment('Admin user ID who replied');
            $table->boolean('is_verified')->nullable()->default(true);
            $table->boolean('is_featured')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
