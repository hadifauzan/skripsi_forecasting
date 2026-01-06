<?php

namespace Database\Seeders;

use App\Models\AffiliateSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AffiliateSubmissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AffiliateSubmission::truncate();
        $submissions = [
            // 1. PENDING - Baru diajukan, menunggu persetujuan admin
            [
                'user_id' => 6,
                'item_id' => 1,
                'status' => AffiliateSubmission::STATUS_PENDING,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '081234567890',
                'shipping_address' => 'Jl. Merdeka No. 123, RT 05/RW 03',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
                'address_notes' => 'Dekat dengan Bank BCA',
                'shipping_courier' => null,
                'tracking_number' => null,
                'video_link' => null,
                'admin_notes' => null,
            ],
            
            // 2. PENDING - Pengajuan kedua untuk testing approve/reject
            [
                'user_id' => 6,
                'item_id' => 5,
                'status' => AffiliateSubmission::STATUS_PENDING,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '082345678901',
                'shipping_address' => 'Jl. Sudirman No. 456, Blok C/15',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40123',
                'address_notes' => 'Komplek Perumahan Green Valley',
                'shipping_courier' => null,
                'tracking_number' => null,
                'video_link' => null,
                'admin_notes' => null,
            ],

            // 3. APPROVED - Sudah disetujui, menunggu input shipping info
            [
                'user_id' => 6,
                'item_id' => 9,
                'status' => AffiliateSubmission::STATUS_APPROVED,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '083456789012',
                'shipping_address' => 'Jl. Gatot Subroto No. 789',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60275',
                'address_notes' => 'Sebelah Apotek Sehat',
                'shipping_courier' => null,
                'tracking_number' => null,
                'video_link' => null,
                'admin_notes' => 'Pengajuan disetujui. Segera lakukan pengiriman.',
            ],

            // 4. SHIPPED - Sedang dalam pengiriman
            [
                'user_id' => 6,
                'item_id' => 13,
                'status' => AffiliateSubmission::STATUS_SHIPPED,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '084567890123',
                'shipping_address' => 'Jl. Diponegoro No. 321',
                'city' => 'Semarang',
                'province' => 'Jawa Tengah',
                'postal_code' => '50241',
                'address_notes' => 'Rumah cat biru, pagar putih',
                'shipping_courier' => 'JNE',
                'tracking_number' => 'JNE1234567890ABC',
                'video_link' => null,
                'admin_notes' => null,
            ],

            // 5. RECEIVED - Sudah diterima, menunggu upload video (masih dalam deadline)
            [
                'user_id' => 6,
                'item_id' => 17,
                'status' => AffiliateSubmission::STATUS_RECEIVED,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '085678901234',
                'shipping_address' => 'Jl. Ahmad Yani No. 654',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'postal_code' => '55223',
                'address_notes' => 'Dekat kampus UGM',
                'shipping_courier' => 'J&T Express',
                'tracking_number' => 'JT9876543210XYZ',
                'video_link' => null,
                'admin_notes' => null,
                'received_at' => now()->subDays(5), // 5 hari yang lalu, masih ada 9 hari lagi
            ],

            // 6. RECEIVED - Overdue, untuk testing mark as failed
            [
                'user_id' => 6,
                'item_id' => 21,
                'status' => AffiliateSubmission::STATUS_RECEIVED,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '086789012345',
                'shipping_address' => 'Jl. Veteran No. 987',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'postal_code' => '65145',
                'address_notes' => 'Samping minimarket',
                'shipping_courier' => 'SiCepat',
                'tracking_number' => 'SICEPAT456789012',
                'video_link' => null,
                'admin_notes' => null,
                'received_at' => now()->subDays(15), // 15 hari yang lalu, sudah overdue
            ],

            // 7. COMPLETED - Sudah selesai dengan video tersubmit
            [
                'user_id' => 6,
                'item_id' => 25,
                'status' => AffiliateSubmission::STATUS_COMPLETED,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '087890123456',
                'shipping_address' => 'Jl. Pahlawan No. 147',
                'city' => 'Medan',
                'province' => 'Sumatera Utara',
                'postal_code' => '20234',
                'address_notes' => 'Gang kecil, rumah no. 5',
                'shipping_courier' => 'POS Indonesia',
                'tracking_number' => 'POS1122334455667',
                'video_link' => 'https://youtu.be/dQw4w9WgXcQ',
                'admin_notes' => null,
                'received_at' => now()->subDays(20),
                'video_submitted_at' => now()->subDays(8),
            ],

            // 8. REJECTED - Ditolak dengan alasan
            [
                'user_id' => 6,
                'item_id' => 25,
                'status' => AffiliateSubmission::STATUS_REJECTED,
                'recipient_name' => 'Affiliate User',
                'recipient_phone' => '088901234567',
                'shipping_address' => 'Jl. Kartini No. 258',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'postal_code' => '30137',
                'address_notes' => 'Komplek Griya Asri',
                'shipping_courier' => null,
                'tracking_number' => null,
                'video_link' => null,
                'admin_notes' => 'Pengajuan ditolak karena alamat tidak lengkap dan nomor telepon tidak aktif.',
            ],

            // 9. FAILED - Gagal karena melewati deadline
            [
                'user_id' => 6,
                'item_id' => 29,
                'status' => AffiliateSubmission::STATUS_FAILED,
                'recipient_name' => 'Maya Sari',
                'recipient_phone' => '089012345678',
                'shipping_address' => 'Jl. Pemuda No. 369',
                'city' => 'Denpasar',
                'province' => 'Bali',
                'postal_code' => '80232',
                'address_notes' => 'Dekat Pasar Badung',
                'shipping_courier' => 'Ninja Xpress',
                'tracking_number' => 'NINJA789012345678',
                'video_link' => null,
                'admin_notes' => null,
                'received_at' => now()->subDays(30), // 30 hari yang lalu, sangat terlambat
            ],
        ];

        foreach ($submissions as $submission) {
            AffiliateSubmission::create($submission);
        }
    }
}
