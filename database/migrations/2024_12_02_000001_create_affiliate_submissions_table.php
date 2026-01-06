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
        Schema::create('affiliate_submissions', function (Blueprint $table) {
            $table->id('submission_id');
            
            // Relasi
            $table->integer('user_id')->comment('Affiliator yang mengajukan');
            $table->integer('item_id')->comment('Barang yang diajukan');
            
            // Status Pengajuan
            // pending: Baru diajukan
            // approved: Disetujui admin (menunggu pengiriman)
            // rejected: Ditolak admin
            // shipped: Admin sudah input resi
            // received: Barang diterima affiliator (Timer 14 hari mulai dari sini)
            // completed: Link video sudah diupload
            // failed: Gagal upload video > 14 hari (Trigger Blacklist)
            $table->enum('status', ['pending', 'approved', 'rejected', 'shipped', 'received', 'completed', 'failed'])
                  ->default('pending')
                  ->comment('Status workflow pengajuan');

            // Data Alamat Pengiriman (Diisi Affiliator saat pengajuan)
            $table->string('recipient_name')->comment('Nama penerima');
            $table->string('recipient_phone')->comment('Nomor HP penerima');
            $table->text('shipping_address')->comment('Alamat lengkap pengiriman');
            $table->string('city')->comment('Kota/Kabupaten');
            $table->string('province')->comment('Provinsi');
            $table->string('postal_code')->nullable()->comment('Kode pos');
            $table->text('address_notes')->nullable()->comment('Catatan alamat (patokan, dll)');
            
            // Data Pengiriman (Diisi Admin)
            $table->string('shipping_courier')->nullable()->comment('Ekspedisi pengiriman');
            $table->string('tracking_number')->nullable()->comment('Nomor Resi');
            
            // Data Konten (Diisi Affiliator)
            $table->text('video_link')->nullable()->comment('Link video promosi');
            
            // Timestamp Logic
            $table->timestamp('approved_at')->nullable()->comment('Waktu disetujui admin');
            $table->timestamp('shipped_at')->nullable()->comment('Waktu admin input resi');
            $table->timestamp('received_at')->nullable()->comment('Waktu barang diterima (Acuan 14 hari)');
            $table->timestamp('video_submitted_at')->nullable()->comment('Waktu upload video');
            
            $table->text('admin_notes')->nullable()->comment('Alasan tolak atau catatan admin');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk performa
            $table->index('user_id');
            $table->index('item_id');
            $table->index('status');
            $table->index('received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_submissions');
    }
};
