<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AffiliateGuide;

class AffiliateGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate only affiliate guide data from master_contents
        DB::table('master_contents')->where('type_of_page', 'affiliate_guide')->delete();

        $guides = [
            // ===== SECTION: PRODUK =====
            [
                'title' => 'Produk yang Tersedia',
                'body' => 'Saat ini, program affiliator kami khusus untuk produk berkualitas tinggi yang telah terbukti bermanfaat bagi banyak pelanggan.',
                'section' => 'produk',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'type' => 'product',
                        'name' => 'Gentle Baby 10ml',
                        'description' => 'Produk perawatan bayi berkualitas tinggi yang aman dan lembut',
                        'color' => 'blue'
                    ],
                    [
                        'type' => 'product',
                        'name' => 'Healo 10ml',
                        'description' => 'Solusi kesehatan keluarga yang telah dipercaya',
                        'color' => 'purple'
                    ]
                ],
                'status' => true
            ],

            // ===== SECTION: PENGAJUAN =====
            [
                'title' => 'Cara Mengajukan Produk',
                'body' => 'Ikuti langkah-langkah berikut untuk mengajukan produk ke program affiliasi. Pastikan semua informasi yang Anda berikan akurat dan lengkap.',
                'section' => 'pengajuan',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'step' => 'a',
                        'text' => 'Buka halaman Produk dari menu navigasi',
                        'color' => 'purple'
                    ],
                    [
                        'step' => 'b',
                        'text' => 'Pilih produk Gentle Baby 10ml atau Healo 10ml',
                        'color' => 'purple'
                    ],
                    [
                        'step' => 'c',
                        'text' => 'Klik tombol "Ajukan Affiliasi" pada produk pilihan',
                        'color' => 'purple'
                    ],
                    [
                        'step' => 'd',
                        'text' => 'Isi formulir pengajuan dengan alamat lengkap dan akurat',
                        'color' => 'purple'
                    ],
                    [
                        'step' => 'e',
                        'text' => 'Klik "Kirim Pengajuan" dan tunggu persetujuan admin',
                        'color' => 'purple'
                    ]
                ],
                'status' => true
            ],
            [
                'title' => 'Tips Pengajuan yang Berhasil',
                'body' => 'Beberapa tips untuk memastikan pengajuan Anda disetujui oleh admin dengan cepat dan lancar.',
                'section' => 'pengajuan',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'type' => 'tip',
                        'text' => 'Pastikan alamat pengiriman lengkap dengan kode pos dan nomor telepon aktif'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Gunakan alamat yang mudah dijangkau oleh kurir'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Cek kembali semua informasi sebelum mengirim pengajuan'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Pastikan nomor telepon yang didaftarkan selalu aktif untuk konfirmasi'
                    ]
                ],
                'status' => true
            ],
            [
                'title' => 'Batasan Pengajuan',
                'body' => 'Untuk menjaga kualitas program dan memastikan setiap affiliator mendapat kesempatan yang sama, kami menerapkan beberapa batasan pengajuan.',
                'section' => 'pengajuan',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'type' => 'note',
                        'title' => 'Satu Produk Sekali Waktu',
                        'text' => 'Anda hanya dapat mengajukan satu produk dalam satu waktu. Tunggu hingga pengajuan selesai sebelum mengajukan produk lain.',
                        'important' => true
                    ],
                    [
                        'type' => 'note',
                        'title' => 'Waktu Persetujuan',
                        'text' => 'Proses persetujuan biasanya memakan waktu 1-3 hari kerja. Anda akan mendapat notifikasi melalui email.'
                    ],
                    [
                        'type' => 'note',
                        'title' => 'Status Pengajuan',
                        'text' => 'Pantau status pengajuan Anda melalui halaman "Pengajuan Saya"'
                    ]
                ],
                'status' => true
            ],

            // ===== SECTION: PENGIRIMAN =====
            [
                'title' => 'Produk Gratis Anda',
                'body' => 'Setelah pengajuan Anda disetujui, kami akan segera memproses pengiriman produk GRATIS ke alamat yang Anda daftarkan.',
                'section' => 'pengiriman',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'check' => true,
                        'text' => 'Kami akan mengirimkan produk GRATIS ke alamat Anda'
                    ],
                    [
                        'check' => true,
                        'text' => 'Pantau status pengiriman di halaman "Pengajuan Saya"'
                    ],
                    [
                        'check' => true,
                        'text' => 'Setelah produk sampai, konfirmasi penerimaan melalui sistem'
                    ]
                ],
                'status' => true
            ],
            [
                'title' => 'Konfirmasi Penerimaan Produk',
                'body' => 'Setelah menerima produk, pastikan untuk mengkonfirmasi penerimaan melalui sistem agar dapat melanjutkan ke tahap berikutnya.',
                'section' => 'pengiriman',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'type' => 'instruction',
                        'text' => 'Buka halaman "Pengajuan Saya"'
                    ],
                    [
                        'type' => 'instruction',
                        'text' => 'Klik tombol "Konfirmasi Penerimaan" pada pengajuan yang sudah dikirim'
                    ],
                    [
                        'type' => 'instruction',
                        'text' => 'Sistem akan mengaktifkan fase upload video review'
                    ],
                    [
                        'type' => 'instruction',
                        'text' => 'Batas waktu konfirmasi: 7 hari setelah status "Dikirim"'
                    ]
                ],
                'status' => true
            ],

            // ===== SECTION: VIDEO =====
            [
                'title' => 'Syarat Video Review',
                'body' => 'Bagian paling penting dari program affiliator! Buat video review yang menarik dan informatif untuk mendapatkan komisi.',
                'section' => 'video',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'requirement' => true,
                        'text' => 'Durasi minimal 30 detik - 1 menit',
                        'bold' => true
                    ],
                    [
                        'requirement' => true,
                        'text' => 'Wajib tampilkan produk yang diterima'
                    ],
                    [
                        'requirement' => true,
                        'text' => 'Jelaskan manfaat dan pengalaman menggunakan produk'
                    ],
                    [
                        'requirement' => true,
                        'text' => 'Video harus asli dan bukan hasil edit/manipulasi'
                    ],
                    [
                        'requirement' => true,
                        'text' => 'Upload ke platform seperti TikTok, Instagram Reels, atau YouTube',
                        'bold' => true
                    ]
                ],
                'status' => true
            ],
            [
                'title' => 'Langkah Upload Video',
                'body' => 'Ikuti langkah-langkah berikut untuk mengupload video review Anda dan mendapatkan persetujuan dari admin.',
                'section' => 'video',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'step' => '1',
                        'text' => 'Setelah menerima dan menggunakan produk, buat video review',
                        'color' => 'red'
                    ],
                    [
                        'step' => '2',
                        'text' => 'Upload video ke platform pilihan Anda (TikTok/Instagram/YouTube)',
                        'color' => 'red'
                    ],
                    [
                        'step' => '3',
                        'text' => 'Copy link video yang sudah di-upload',
                        'color' => 'red'
                    ],
                    [
                        'step' => '4',
                        'text' => 'Buka halaman "Pengajuan Saya" dan masukkan link video',
                        'color' => 'red'
                    ]
                ],
                'status' => true
            ],
            [
                'title' => 'Batas Waktu Upload',
                'body' => 'Perhatikan batas waktu upload video agar pengajuan Anda tidak dibatalkan secara otomatis.',
                'section' => 'video',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'type' => 'warning',
                        'title' => 'Batas Waktu:',
                        'text' => 'Upload video maksimal 14 hari setelah konfirmasi penerimaan produk. Lewat dari batas waktu, pengajuan akan otomatis dibatalkan.',
                        'important' => true,
                        'bold' => true
                    ]
                ],
                'status' => true
            ],
            [
                'title' => 'Tips Video yang Menarik',
                'body' => 'Beberapa tips untuk membuat video review yang menarik dan meningkatkan peluang disetujui oleh admin.',
                'section' => 'video',
                'type_of_page' => 'affiliate_guide',
                'sub_items' => [
                    [
                        'type' => 'tip',
                        'text' => 'Gunakan pencahayaan yang baik agar produk terlihat jelas'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Jelaskan dengan natural dan jujur tentang pengalaman Anda'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Tampilkan kemasan produk dan cara penggunaan'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Gunakan musik latar yang sesuai dan tidak mengganggu'
                    ],
                    [
                        'type' => 'tip',
                        'text' => 'Tambahkan caption atau subtitle untuk penjelasan tambahan'
                    ]
                ],
                'status' => true
            ]
        ];

        foreach ($guides as $guide) {
            AffiliateGuide::create($guide);
        }
    }
}



