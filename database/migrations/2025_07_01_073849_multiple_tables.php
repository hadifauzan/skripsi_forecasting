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
            $table->integer('item_categories_id')->nullable();
            $table->string('code_item')->nullable();
            $table->string('name_item')->nullable();
            $table->text('description_item')->nullable();
            $table->text('ingredient_item')->nullable();
            $table->string('netweight_item')->nullable();
            $table->text('contain_item')->nullable();
            $table->decimal('costprice_item', 15, 2)->nullable();
            $table->decimal('sellingprice_item', 15, 2)->nullable();
            $table->integer('current_inventory')->nullable();
            $table->integer('forecast_demand')->nullable();
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
            $table->decimal('avg_daily_sales', 15, 4)->default(0)
                  ->comment('Rata-rata penjualan harian (auto-update dari finished_goods_out)');
            $table->integer('buffer_stock')->default(0);
            $table->date('last_reorder_at')->nullable();
            $table->enum('stock_status', ['normal', 'low', 'critical', 'overstock'])
                  ->default('normal');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_items_raw_material', function (Blueprint $table) {
            $table->id('item_raw_id');
            $table->string('material_name');
            $table->string('unit');
            $table->decimal('purchase_price', 15, 2);
            $table->integer('current_stock')->default(0);
            $table->decimal('avg_daily_usage', 15, 4)->default(0);
            $table->date('last_reorder_date')->nullable();
            $table->enum('stock_status', ['normal', 'low', 'critical', 'overstock'])
                  ->default('normal');
            $table->integer('lead_time_days');
            $table->integer('buffer_stock')->default(0);
            $table->integer('reorder_point')->default(0);
            $table->string('supplier_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_items_bill_of_materials', function (Blueprint $table) {
            $table->id('bom_id');
            $table->integer('item_id');
            $table->integer('item_raw_id');
            $table->decimal('quantity_required', 15, 4);
            $table->decimal('yield_percentage', 5, 2);
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
            $table->date('stock_opname_date');
            $table->string('officer_name');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'approved', 'cancelled'])->default('draft');
            $table->string('batch_code')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('inventory_id')->nullable();
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
            $table->integer('stock_opname_batches_id')->unsigned();
            $table->integer('item_id')->nullable();
            $table->enum('item_type', ['raw_material', 'finished_good'])->nullable();
            $table->string('item_name')->nullable();
            $table->decimal('system_stock', 15, 2)->nullable()->default(0);
            $table->decimal('physical_count', 15, 2)->nullable();
            $table->decimal('difference', 15, 2)->nullable()->default(0);
            $table->decimal('unit_cost', 15, 2)->nullable()->default(0);
            $table->decimal('value_impact', 15, 2)->nullable()->default(0);
            $table->decimal('buffer_stock_at_opname', 15, 4)->nullable()->default(0);
            $table->decimal('reorder_point_at_opname', 15, 4)->nullable()->default(0);
            $table->boolean('is_below_buffer')->nullable()->default(false);
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

        Schema::create('production_orders', function (Blueprint $table) {
            $table->id('production_order_id');
            // Relasi
            $table->unsignedBigInteger('item_id')->comment('FK ke master_items (barang jadi target)');
            $table->unsignedBigInteger('branch_id')->comment('FK ke master_branches');
            $table->unsignedBigInteger('created_by')->comment('FK ke master_users');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('FK ke master_users');
            // Identitas order
            $table->string('order_number', 50)->unique()->comment('Nomor order produksi, e.g. PRD-20250701-001');
            // Kuantitas
            $table->decimal('qty_planned', 15, 2)->default(0)->comment('Jumlah yang direncanakan diproduksi');
            $table->decimal('qty_produced', 15, 2)->default(0)->comment('Jumlah yang sudah selesai diproduksi');
            $table->string('unit', 30)->nullable()->comment('Satuan: pcs, kg, liter, dll');
            // Status & waktu
            $table->enum('status', ['draft', 'approved', 'in_progress', 'completed', 'cancelled'])
                  ->default('draft')
                  ->comment('Draft=baru dibuat, approved=disetujui, in_progress=sedang berjalan, completed=selesai, cancelled=dibatalkan');
            $table->date('planned_date')->nullable()->comment('Tanggal rencana produksi');
            $table->timestamp('started_at')->nullable()->comment('Waktu mulai produksi');
            $table->timestamp('completed_at')->nullable()->comment('Waktu selesai produksi');
            // Biaya
            $table->decimal('total_material_cost', 15, 2)->default(0)->comment('Total biaya bahan baku yang digunakan');
            $table->decimal('overhead_cost', 15, 2)->default(0)->comment('Biaya overhead produksi');
            $table->decimal('hpp_per_unit', 15, 2)->default(0)->comment('HPP per unit = (total_material_cost + overhead_cost) / qty_produced');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('item_id');
            $table->index('branch_id');
            $table->index('status');
            $table->index('planned_date');
        });

        Schema::create('raw_material_in', function (Blueprint $table) {
            $table->id('raw_material_in_id');
            // Relasi
            $table->unsignedBigInteger('item_raw_id')->comment('FK ke master_items_raw_material');
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('FK ke master_suppliers');
            $table->unsignedBigInteger('transaction_purchase_detail_id')->nullable()->comment('FK ke transaction_purchases_details jika dari pembelian');
            $table->unsignedBigInteger('branch_id')->comment('FK ke master_branches (gudang tujuan)');
            $table->unsignedBigInteger('received_by')->comment('FK ke master_users');
            // Identitas dokumen
            $table->string('document_number', 50)->nullable()->comment('Nomor dokumen penerimaan, e.g. RMI-20250701-001');
            $table->string('po_number', 50)->nullable()->comment('Nomor Purchase Order referensi');
            $table->string('batch_number', 50)->nullable()->comment('Nomor batch dari supplier');
            // Kuantitas & harga
            $table->decimal('qty_ordered', 15, 4)->default(0)->comment('Jumlah yang dipesan');
            $table->decimal('qty_received', 15, 4)->default(0)->comment('Jumlah yang diterima (aktual)');
            $table->decimal('qty_rejected', 15, 4)->default(0)->comment('Jumlah yang ditolak/retur');
            $table->string('unit', 30)->nullable()->comment('Satuan: kg, liter, gram, pcs, dll');
            $table->decimal('unit_cost', 15, 4)->default(0)->comment('Harga beli per satuan');
            $table->decimal('total_cost', 15, 2)->default(0)->comment('Total biaya = qty_received × unit_cost');
            // Stok sebelum dan sesudah (untuk audit)
            $table->decimal('stock_before', 15, 4)->default(0)->comment('Stok bahan baku sebelum penerimaan');
            $table->decimal('stock_after', 15, 4)->default(0)->comment('Stok bahan baku sesudah penerimaan');
            // Informasi tambahan
            $table->date('received_date')->comment('Tanggal penerimaan');
            $table->date('expired_date')->nullable()->comment('Tanggal kadaluarsa (jika ada)');
            $table->enum('condition', ['good', 'damaged', 'near_expired'])->default('good')->comment('Kondisi barang diterima');
            $table->string('storage_location', 100)->nullable()->comment('Lokasi penyimpanan di gudang');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('item_raw_id');
            $table->index('supplier_id');
            $table->index('received_date');
            $table->index('batch_number');
        });

        Schema::create('raw_material_out', function (Blueprint $table) {
            $table->id('raw_material_out_id');
            // Relasi
            $table->unsignedBigInteger('item_raw_id')->comment('FK ke master_items_raw_material');
            $table->unsignedBigInteger('production_order_id')->nullable()->comment('FK ke production_orders (jika untuk produksi)');
            $table->unsignedBigInteger('bom_id')->nullable()->comment('FK ke master_items_bill_of_materials');
            $table->unsignedBigInteger('branch_id')->comment('FK ke master_branches');
            $table->unsignedBigInteger('issued_by')->comment('FK ke master_users');
            // Identitas dokumen
            $table->string('document_number', 50)->nullable()->comment('Nomor dokumen pengeluaran, e.g. RMO-20250701-001');
            // Kuantitas
            $table->decimal('qty_requested', 15, 4)->default(0)->comment('Jumlah yang diminta');
            $table->decimal('qty_issued', 15, 4)->default(0)->comment('Jumlah yang benar-benar dikeluarkan');
            $table->string('unit', 30)->nullable()->comment('Satuan: kg, liter, gram, pcs, dll');
            $table->decimal('unit_cost', 15, 4)->default(0)->comment('Harga pokok per satuan saat keluar');
            $table->decimal('total_cost', 15, 2)->default(0)->comment('Total biaya = qty_issued × unit_cost');
            // Stok sebelum dan sesudah (untuk audit trail)
            $table->decimal('stock_before', 15, 4)->default(0)->comment('Stok bahan baku sebelum pengeluaran');
            $table->decimal('stock_after', 15, 4)->default(0)->comment('Stok bahan baku sesudah pengeluaran');
            // Alasan & tanggal
            $table->enum('reason', ['production', 'sample', 'waste', 'expired', 'adjustment', 'return_to_supplier', 'other'])
                  ->default('production')
                  ->comment('Alasan pengeluaran bahan baku');
            $table->date('issued_date')->comment('Tanggal pengeluaran');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('item_raw_id');
            $table->index('production_order_id');
            $table->index('issued_date');
            $table->index('reason');
        });

        Schema::create('finished_goods_in', function (Blueprint $table) {
            $table->id('fg_in_id');
            // Relasi
            $table->unsignedBigInteger('item_id')->comment('FK ke master_items (barang jadi)');
            $table->unsignedBigInteger('production_order_id')->nullable()->comment('FK ke production_orders');
            $table->unsignedBigInteger('inventory_id')->comment('FK ke master_inventories (gudang tujuan)');
            $table->unsignedBigInteger('branch_id')->comment('FK ke master_branches');
            $table->unsignedBigInteger('received_by')->comment('FK ke master_users');
            // Identitas
            $table->string('document_number', 50)->nullable()->comment('Nomor dokumen, e.g. FGI-20250701-001');
            $table->string('batch_number', 50)->nullable()->comment('Nomor batch produksi');
            // Kuantitas & biaya
            $table->decimal('qty_received', 15, 2)->default(0)->comment('Jumlah barang jadi yang diterima di gudang');
            $table->string('unit', 30)->nullable()->comment('Satuan: pcs, lusin, karton, dll');
            $table->decimal('unit_cost', 15, 4)->default(0)->comment('HPP per unit dari production_order');
            $table->decimal('total_cost', 15, 2)->default(0)->comment('Total biaya masuk = qty_received × unit_cost');
            // Stok sebelum dan sesudah
            $table->decimal('stock_before', 15, 2)->default(0)->comment('Stok barang jadi sebelum penerimaan');
            $table->decimal('stock_after', 15, 2)->default(0)->comment('Stok barang jadi sesudah penerimaan');
            // Informasi tambahan
            $table->date('production_date')->nullable()->comment('Tanggal produksi barang');
            $table->date('received_date')->comment('Tanggal diterima di gudang');
            $table->date('expired_date')->nullable()->comment('Tanggal kadaluarsa produk');
            $table->string('storage_location', 100)->nullable()->comment('Lokasi penyimpanan di gudang');
            $table->enum('qc_status', ['pending', 'passed', 'failed', 'conditional'])
                  ->default('pending')
                  ->comment('Status quality control');
            $table->text('qc_notes')->nullable()->comment('Catatan hasil QC');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('item_id');
            $table->index('production_order_id');
            $table->index('inventory_id');
            $table->index('received_date');
        });

        Schema::create('finished_goods_out', function (Blueprint $table) {
            $table->id('fg_out_id');
            // Relasi
            $table->unsignedBigInteger('item_id')->comment('FK ke master_items (barang jadi)');
            $table->unsignedBigInteger('inventory_id')->comment('FK ke master_inventories (gudang asal)');
            $table->unsignedBigInteger('branch_id')->comment('FK ke master_branches');
            $table->unsignedBigInteger('transaction_sales_detail_id')->nullable()->comment('FK ke transaction_sales_details (jika dari penjualan)');
            $table->unsignedBigInteger('issued_by')->comment('FK ke master_users');
            // Identitas
            $table->string('document_number', 50)->nullable()->comment('Nomor dokumen, e.g. FGO-20250701-001');
            // Kuantitas & biaya
            $table->decimal('qty_out', 15, 2)->default(0)->comment('Jumlah barang jadi yang keluar');
            $table->string('unit', 30)->nullable()->comment('Satuan: pcs, lusin, karton, dll');
            $table->decimal('unit_cost', 15, 4)->default(0)->comment('HPP per unit saat keluar (FIFO/weighted average)');
            $table->decimal('total_cost', 15, 2)->default(0)->comment('Total HPP = qty_out × unit_cost');
            // Stok sebelum dan sesudah
            $table->decimal('stock_before', 15, 2)->default(0)->comment('Stok barang jadi sebelum pengeluaran');
            $table->decimal('stock_after', 15, 2)->default(0)->comment('Stok barang jadi sesudah pengeluaran');
            // Alasan & tanggal
            $table->enum('type', ['sale', 'sample', 'damaged', 'expired', 'return', 'transfer', 'adjustment', 'other'])
                  ->default('sale')
                  ->comment('Jenis pengeluaran barang jadi');
            $table->date('out_date')->comment('Tanggal pengeluaran');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('item_id');
            $table->index('inventory_id');
            $table->index('transaction_sales_detail_id');
            $table->index('out_date');
            $table->index('type');
        });

        Schema::create('buffer_stock_config', function (Blueprint $table) {
            $table->id('config_id');

            // Target item (pilih salah satu)
            $table->enum('item_type', ['raw_material', 'finished_good'])
                  ->comment('Jenis item: raw_material = bahan baku, finished_good = barang jadi');
            $table->unsignedBigInteger('item_id')->comment('ID item sesuai item_type: FK ke master_items_raw_material ATAU master_items');
            $table->unsignedBigInteger('branch_id')->nullable()->comment('FK ke master_branches, NULL = berlaku untuk semua cabang');

            // Parameter input (diisi manual atau dari historis)
            $table->decimal('avg_daily_usage', 15, 4)->default(0)
                  ->comment('Rata-rata pemakaian per hari (auto-kalkulasi dari 30 hari terakhir)');
            $table->decimal('max_daily_usage', 15, 4)->default(0)
                  ->comment('Pemakaian maksimum per hari (untuk worst-case buffer)');
            $table->integer('lead_time_days')->default(0)
                  ->comment('Waktu tunggu pemesanan dalam hari (dari order sampai terima)');
            $table->integer('max_lead_time_days')->default(0)
                  ->comment('Lead time maksimum dalam hari (untuk worst-case buffer)');
            $table->integer('safety_days')->default(7)
                  ->comment('Jumlah hari stok pengaman (default 7 hari)');
            $table->integer('review_period_days')->default(30)
                  ->comment('Periode review ulang stok dalam hari');

            // Hasil kalkulasi (auto-update via observer/scheduler)
            $table->decimal('buffer_stock', 15, 4)->default(0)
                  ->comment('[KALKULASI] = avg_daily_usage × safety_days');
            $table->decimal('reorder_point', 15, 4)->default(0)
                  ->comment('[KALKULASI] = (avg_daily_usage × lead_time_days) + buffer_stock');
            $table->decimal('max_stock', 15, 4)->default(0)
                  ->comment('[KALKULASI] = reorder_point + (avg_daily_usage × review_period_days)');
            $table->decimal('economic_order_qty', 15, 4)->default(0)
                  ->comment('[KALKULASI EOQ] = sqrt(2 × avg_daily_usage × 365 × ordering_cost / holding_cost_per_unit)');

            // Parameter untuk EOQ (opsional)
            $table->decimal('ordering_cost', 15, 2)->default(0)
                  ->comment('Biaya sekali pemesanan (untuk kalkulasi EOQ)');
            $table->decimal('holding_cost_per_unit', 15, 4)->default(0)
                  ->comment('Biaya penyimpanan per unit per tahun (untuk kalkulasi EOQ)');

            // Metode kalkulasi
            $table->enum('calculation_method', ['fixed', 'moving_average', 'worst_case'])
                  ->default('moving_average')
                  ->comment('fixed=nilai manual, moving_average=rata-rata 30 hari, worst_case=pakai max_daily_usage & max_lead_time');
            $table->boolean('is_active')->default(true)->comment('Aktif/nonaktif konfigurasi ini');
            $table->timestamp('last_calculated_at')->nullable()->comment('Waktu terakhir kalkulasi dijalankan');

            // Siapa yang mengatur
            $table->unsignedBigInteger('created_by')->nullable()->comment('FK ke master_users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('FK ke master_users');

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['item_type', 'item_id', 'branch_id'], 'unique_buffer_config');
            $table->index(['item_type', 'item_id']);
            $table->index('is_active');
        });

        Schema::create('stock_adjustment', function (Blueprint $table) {
            $table->id('adjustment_id');
            // Sumber penyesuaian
            $table->unsignedBigInteger('stock_opname_details_id')->nullable()
                  ->comment('FK ke stock_opname_details (jika dari stock opname)');
            $table->unsignedBigInteger('stock_opname_batches_id')->nullable()
                  ->comment('FK ke stock_opname_batches');
            // Target item
            $table->enum('item_type', ['raw_material', 'finished_good'])
                  ->comment('raw_material = bahan baku, finished_good = barang jadi');
            $table->unsignedBigInteger('item_id')->comment('ID item sesuai item_type');
            $table->unsignedBigInteger('inventory_id')->nullable()->comment('FK ke master_inventories');
            $table->unsignedBigInteger('branch_id')->comment('FK ke master_branches');
            // Dokumen
            $table->string('document_number', 50)->nullable()->comment('Nomor dokumen penyesuaian, e.g. ADJ-20250701-001');
            // Kuantitas
            $table->decimal('qty_system', 15, 4)->default(0)->comment('Stok menurut sistem sebelum penyesuaian');
            $table->decimal('qty_physical', 15, 4)->default(0)->comment('Stok fisik hasil hitung opname');
            $table->decimal('qty_difference', 15, 4)->default(0)
                  ->comment('Selisih = qty_physical - qty_system (negatif = kurang, positif = lebih)');
            $table->decimal('qty_after_adjustment', 15, 4)->default(0)->comment('Stok setelah penyesuaian');
            $table->string('unit', 30)->nullable()->comment('Satuan item');
            // Nilai
            $table->decimal('unit_cost', 15, 4)->default(0)->comment('HPP per unit saat penyesuaian');
            $table->decimal('value_impact', 15, 2)->default(0)
                  ->comment('Dampak nilai = qty_difference × unit_cost (negatif = kerugian)');
            // Alasan & persetujuan
            $table->enum('reason', ['opname_result', 'damaged', 'expired', 'missing', 'system_error', 'manual', 'other'])
                  ->default('opname_result')
                  ->comment('Alasan penyesuaian stok');
            $table->enum('adjustment_type', ['increase', 'decrease'])
                  ->comment('increase = stok bertambah, decrease = stok berkurang');
            $table->unsignedBigInteger('adjusted_by')->comment('FK ke master_users (yang melakukan penyesuaian)');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('FK ke master_users (yang menyetujui)');
            $table->timestamp('adjusted_at')->useCurrent()->comment('Waktu penyesuaian dilakukan');
            $table->timestamp('approved_at')->nullable()->comment('Waktu penyesuaian disetujui');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['item_type', 'item_id']);
            $table->index('stock_opname_batches_id');
            $table->index('adjusted_at');
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
        Schema::dropIfExists('master_items_raw_material');
        Schema::dropIfExists('master_items_bill_of_materials');
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
        Schema::dropIfExists('stock_adjustment');
        Schema::dropIfExists('buffer_stock_config');
        Schema::dropIfExists('finished_goods_out');
        Schema::dropIfExists('finished_goods_in');
        Schema::dropIfExists('raw_material_out');
        Schema::dropIfExists('raw_material_in');
        Schema::dropIfExists('production_orders');
    }
};