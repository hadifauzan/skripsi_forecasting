<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('name_company')->nullable();
            $table->string('phone_company')->nullable();
            $table->string('address_company')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->integer('company_id');
            $table->integer('role_id');
            $table->string('profile_picture')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('must_change_password')->default(false);
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->text('profession')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('instagram_account')->nullable();
            $table->string('tiktok_account')->nullable();
            $table->string('shopee_account')->nullable();
            $table->string('other_source')->nullable();
            $table->text('source_info')->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif', 'Pending'])->nullable()->default('Pending');
            $table->text('notes')->nullable();
            $table->rememberToken();
            $table->string('jwt_token', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->integer('company_id');
            $table->string('name_branch')->nullable();
            $table->string('phone_branch')->nullable();
            $table->string('address_branch')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_menus', function (Blueprint $table) {
            $table->id('menu_id');
            $table->string('title')->nullable();
            $table->string('route')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_users_branches', function (Blueprint $table) {
            $table->id('user_branch_id');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_users_access', function (Blueprint $table) {
            $table->id('access_id');
            $table->integer('user_id');
            $table->integer('menu_id');
            $table->boolean('can_create')->default(false)->comment('Permission untuk Create/Add data');
            $table->boolean('can_read')->default(true)->comment('Permission untuk Read/View data');
            $table->boolean('can_update')->default(false)->comment('Permission untuk Update/Edit data');
            $table->boolean('can_delete')->default(false)->comment('Permission untuk Delete data');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name_category')->nullable(); // Fixed: should be string, not foreignId
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->integer('company_id');
            $table->string('code_item')->nullable();
            $table->string('name_item')->nullable();
            $table->text('description_item')->nullable();
            $table->text('ingredient_item')->nullable();
            $table->string('netweight_item')->nullable();
            $table->text('contain_item')->nullable();
            $table->double('costprice_item')->nullable();
            $table->string('picture_item')->nullable()->comment('Path to item image');
            $table->string('status_item')->nullable();
            $table->boolean('is_reseller_babyspa')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_items_categories', function (Blueprint $table) {
            $table->id('item_categories_id');
            $table->integer('categories_id');
            $table->string('item_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_customers_types', function (Blueprint $table) {
            $table->id('customer_type_id');
            $table->string('name_customer_type')->nullable(); // Fixed: should be string, not foreignId
            $table->string('reseller')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('name_role')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_inventories', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->integer('branch_id')->nullable();
            $table->string('name_inventory')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_items_stock', function (Blueprint $table) {
            $table->id('item_stock_id');
            $table->integer('item_id');
            $table->integer('inventory_id');
            $table->integer('stock')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->integer('company_id');
            $table->integer('customer_type_id');
            $table->string('email_customer')->nullable();
            $table->string('name_customer')->nullable();
            $table->string('phone_customer')->nullable();
            $table->string('address_customer')->nullable();
            $table->string('social_media')->nullable();
            $table->string('sales_platform')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('location_notes')->nullable()->comment('Catatan lokasi atau alamat lengkap');
            $table->enum('status', ['Aktif', 'Nonaktif', 'Pending'])->nullable()->default('Pending')->comment('Status customer/reseller');
            $table->string('point')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('jwt_token', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_suppliers', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->integer('company_id');
            $table->string('name_supplier')->nullable();
            $table->string('phone_supplier')->nullable();
            $table->string('address_supplier')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('jwt_token', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_items_details', function (Blueprint $table) {
            $table->id('item_detail_id');
            $table->integer('item_id');
            $table->integer('customer_type_id');
            $table->double('cost_price')->nullable();
            $table->double('sell_price')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_sales_types', function (Blueprint $table) {
            $table->id('sales_type_id');
            $table->string('name_sales_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_payment_methods', function (Blueprint $table) {
            $table->id('payment_method_id');
            $table->string('name_payment_method')->nullable(); // Fixed: should be string, not double
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_category_articles', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name_category')->nullable(); // Fixed: should be string, not foreignId
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_sales', function (Blueprint $table) {
            $table->id('transaction_sales_id');
            $table->integer('branch_id');
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->integer('sales_type_id');
            $table->integer('expedition_id');
            $table->string('number')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('notes')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->double('total_amount')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->string('shipping_courier')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('shipping_etd')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('shipping_status')->nullable();
            $table->text('shipping_notes')->nullable();
            $table->string('shipping_city_id')->nullable();
            $table->string('shipping_province_id')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('shipping_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_sales_details', function (Blueprint $table) {
            $table->id('transaction_sales_detail_id');
            $table->integer('transaction_sales_id');
            $table->integer('item_id');
            $table->integer('qty')->nullable();
            $table->double('costprice')->nullable();
            $table->double('sell_price')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->double('total_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_sales_users', function (Blueprint $table) {
            $table->id('transaction_sales_user_id');
            $table->integer('transaction_sales_id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->integer('qty')->nullable();
            $table->double('revenue')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_purchases', function (Blueprint $table) {
            $table->id('transaction_purchase_id');
            $table->integer('supplier_id');
            $table->integer('branch_id');
            $table->string('number')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('notes')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_purchases_details', function (Blueprint $table) {
            $table->id('transaction_purchase_detail_id');
            $table->integer('transaction_purchase_id');
            $table->integer('item_id');
            $table->integer('qty')->nullable();
            $table->double('costprice')->nullable();
            $table->double('sell_price')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->double('total_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->id('transaction_payment_id');
            $table->integer('transaction_purchase_id')->nullable();
            $table->integer('transaction_sales_id')->nullable();
            $table->integer('payment_method_id');
            $table->double('amount'); // Selalu nilai positif
            $table->double('received_amount')->nullable(); // Untuk sales: uang yang diterima
            $table->double('change_amount')->nullable(); // Untuk sales: kembalian
            $table->enum('payment_type', ['incoming', 'outgoing']);
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overpaid'])->default('pending');
            $table->dateTime('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('operational_expenses', function (Blueprint $table) {
            $table->id('operational_expense_id');
            $table->string('expense_number')->nullable();
            $table->dateTime('expense_date');
            $table->integer('branch_id');
            $table->integer('user_id');
            $table->integer('payment_method_id');
            $table->integer('transaction_payment_id')->unique()->nullable();
            $table->string('expense_category')->nullable();
            $table->text('item_description');
            $table->double('total_amount');
            $table->string('receipt_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_stocks', function (Blueprint $table) {
            $table->id('transaction_stocks_id');
            $table->integer('transaction_purchase_detail_id')->nullable();
            $table->integer('transaction_sales_detail_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->integer('inventory_origin_id')->nullable();
            $table->integer('inventory_destination_id')->nullable();
            $table->integer('stock_incoming')->nullable();
            $table->integer('stock_outgoing')->nullable();

            // Historical stock fields - stock sebelum transaksi
            $table->integer('stock_before_origin')->nullable()->comment('Stock di inventory origin sebelum transaksi');
            $table->integer('stock_before_destination')->nullable()->comment('Stock di inventory destination sebelum transaksi');

            // Historical stock fields - stock sesudah transaksi
            $table->integer('stock_after_origin')->nullable()->comment('Stock di inventory origin sesudah transaksi');
            $table->integer('stock_after_destination')->nullable()->comment('Stock di inventory destination sesudah transaksi');

            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Opname Batches
        Schema::create('stock_opname_batches', function (Blueprint $table) {
            $table->id('stock_opname_batches_id');
            $table->string('batch_code')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('inventory_id')->nullable();
            $table->string('status')->nullable()->default('draft'); // 'draft', 'in_progress', 'completed', 'approved', 'cancelled'
            $table->integer('created_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Opname Details
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id('stock_opname_details_id');
            $table->integer('batch_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->decimal('system_stock', 15, 2)->nullable()->default(0);
            $table->decimal('physical_count', 15, 2)->nullable();
            $table->decimal('difference', 15, 2)->nullable()->default(0);
            $table->decimal('unit_cost', 15, 2)->nullable()->default(0);
            $table->decimal('value_impact', 15, 2)->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->string('count_status')->nullable()->default('pending'); // 'pending', 'counted', 'verified'
            $table->integer('counted_by')->nullable();
            $table->integer('verified_by')->nullable();
            $table->timestamp('counted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Opname Photos
        Schema::create('stock_opname_photos', function (Blueprint $table) {
            $table->id('stock_opname_photos_id');
            $table->integer('opname_detail_id')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('photo_type')->nullable(); // 'damaged', 'missing', 'excess', 'normal'
            $table->text('description')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Opname Approvals
        Schema::create('stock_opname_approvals', function (Blueprint $table) {
            $table->id('stock_opname_approvals_id');
            $table->integer('stock_opname_batches_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('level')->nullable()->default('supervisor'); // 'supervisor', 'manager', 'director'
            $table->string('status')->nullable()->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Opname History
        Schema::create('stock_opname_histories', function (Blueprint $table) {
            $table->id('stock_opname_history_id');
            $table->integer('stock_opname_batches_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('accuracy_percentage', 5, 2)->nullable()->default(0);
            $table->decimal('total_items', 15, 2)->nullable()->default(0);
            $table->decimal('accurate_items', 15, 2)->nullable()->default(0);
            $table->decimal('total_value_impact', 15, 2)->nullable()->default(0);
            $table->date('opname_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_expeditions', function (Blueprint $table) {
            $table->id('expedition_id');
            $table->string('name_expedition')->nullable();
            $table->string('phone_expedition')->nullable();
            $table->string('address_expedition')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_contents', function (Blueprint $table) {
            $table->id('content_id');
            $table->integer('item_id')->nullable();
            $table->enum('type_of_page', ['homepage', 'about_us', 'partner', 'gentle_baby_product', 'nyam_product', 'mamina_product', 'healo_product', 'article', 'reseller', 'affiliate_guide'])->nullable();
            $table->string('section')->nullable();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->json('sub_items')->nullable();
            $table->string('video_url')->nullable();
            $table->string('username')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->integer('user_id')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->string('shipping_courier')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('shipping_etd')->nullable();
            $table->string('tracking_number')->nullable();
            $table->integer('shipping_city_id')->nullable();
            $table->integer('shipping_province_id')->nullable();
            $table->string('shipping_status')->nullable();
            $table->text('shipping_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('master_item_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->string('item_name')->nullable(); // Simpan nama item saat order untuk historical data
            $table->string('item_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->default('qris');
            $table->decimal('gross_amount', 15, 2);
            $table->string('transaction_status')->default('pending');
            $table->json('midtrans_response')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stock_opname_histories');
        Schema::dropIfExists('stock_opname_approvals');
        Schema::dropIfExists('stock_opname_photos');
        Schema::dropIfExists('stock_opname_details');
        Schema::dropIfExists('stock_opname_batches');
        Schema::dropIfExists('operational_expenses');
        Schema::dropIfExists('transaction_payments');
        Schema::dropIfExists('transaction_stocks');
        Schema::dropIfExists('transaction_purchases_details');
        Schema::dropIfExists('transaction_purchases');
        Schema::dropIfExists('transaction_sales_users');
        Schema::dropIfExists('transaction_sales_details');
        Schema::dropIfExists('transaction_sales');
        Schema::dropIfExists('master_payment_methods');
        Schema::dropIfExists('master_sales_types');
        Schema::dropIfExists('master_items_details');
        Schema::dropIfExists('master_suppliers');
        Schema::dropIfExists('master_customers');
        Schema::dropIfExists('master_items_stock');
        Schema::dropIfExists('master_inventories');
        Schema::dropIfExists('master_customers_types');
        Schema::dropIfExists('master_items');
        Schema::dropIfExists('master_categories');
        Schema::dropIfExists('master_category_articles');
        Schema::dropIfExists('master_users_access');
        Schema::dropIfExists('master_users_branches');
        Schema::dropIfExists('master_menus');
        Schema::dropIfExists('master_branches');
        Schema::dropIfExists('master_users');
        Schema::dropIfExists('master_companies');
        Schema::dropIfExists('master_expeditions');
        Schema::dropIfExists('master_roles');
        Schema::dropIfExists('master_items_categories');
        Schema::dropIfExists('master_contents');
    }
};