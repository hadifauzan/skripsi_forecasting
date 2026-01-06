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
        Schema::table('orders', function (Blueprint $table) {
            // Auto confirmation tracking timestamps
            $table->timestamp('shipped_at')->nullable()->after('shipping_notes')->comment('Waktu pesanan dikirim oleh admin');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at')->comment('Waktu pesanan dikonfirmasi diterima');
            $table->timestamp('confirmed_at')->nullable()->after('delivered_at')->comment('Waktu konfirmasi (manual/auto)');
            
            // Auto confirmation flags
            $table->boolean('auto_confirmed')->default(false)->after('confirmed_at')->comment('Apakah dikonfirmasi otomatis');
            $table->enum('confirmation_type', ['manual', 'auto'])->nullable()->after('auto_confirmed')->comment('Tipe konfirmasi');
            $table->text('auto_confirmation_notes')->nullable()->after('confirmation_type')->comment('Catatan auto confirmation');
            
            // Admin tracking
            $table->integer('shipped_by')->nullable()->after('auto_confirmation_notes')->comment('User ID admin yang mengirim pesanan');
            $table->text('admin_notes')->nullable()->after('shipped_by')->comment('Catatan admin untuk pesanan');
            
            // Indexes untuk performance
            $table->index('shipped_at');
            $table->index('delivered_at');
            $table->index('auto_confirmed');
            $table->index('shipped_by');
            $table->index('confirmation_type');
            $table->index(['status', 'shipped_at', 'auto_confirmed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['shipped_at']);
            $table->dropIndex(['delivered_at']);
            $table->dropIndex(['auto_confirmed']);
            $table->dropIndex(['shipped_by']);
            $table->dropIndex(['confirmation_type']);
            $table->dropIndex(['status', 'shipped_at', 'auto_confirmed']);
            
            $table->dropColumn([
                'shipped_at',
                'delivered_at', 
                'confirmed_at',
                'auto_confirmed',
                'confirmation_type',
                'auto_confirmation_notes',
                'shipped_by',
                'admin_notes'
            ]);
        });
    }
};
