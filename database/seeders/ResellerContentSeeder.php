<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterContent;

class ResellerContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing reseller content first
        MasterContent::where('type_of_page', 'reseller')->delete();

        // Banner Section (Reseller Page)
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'banner_reseller'
            ],
            [
                'title' => 'Bergabung Sebagai Reseller',
                'body' => 'Dapatkan peluang bisnis dengan menjadi reseller produk baby wellness terpercaya. Komisi menarik dan dukungan penuh untuk kesuksesan Anda.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        // Why Join Us Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-why-join-title'
            ],
            [
                'title' => 'Why Join Us',
                'body' => 'Kami percaya produk ini cocok untuk audiens seperti moms, new parents, breastfeeding moms, dan pejuang MPASI. Bergabunglah dan dapatkan penghasilan tambahan dengan berbagi produk yang bermanfaat.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        // Benefits Section (Why Join Us Items)
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-benefit-1'
            ],
            [
                'title' => 'Komisi Kompetitif',
                'body' => 'Dapatkan komisi menarik setiap penjualan.',
                'image' => 'dollar',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-benefit-2'
            ],
            [
                'title' => 'Materi Promosi',
                'body' => 'Kami sediakan script dan materi promosi siap pakai.',
                'image' => 'gift',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-benefit-3'
            ],
            [
                'title' => 'Dukungan Tim',
                'body' => 'Support dan pelatihan singkat untuk mitra baru.',
                'image' => 'support',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-benefit-4'
            ],
            [
                'title' => 'Hadiah & Insentif',
                'body' => 'Program reward untuk performa terbaik.',
                'image' => 'trophy',
                'item_id' => null,
                'status' => true,
            ]
        );

        // What You Get Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-what-you-get-title'
            ],
            [
                'title' => 'What you will get',
                'body' => 'Benefit dan alat yang akan kamu dapatkan ketika bergabung.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-get-1'
            ],
            [
                'title' => 'Materi Konten',
                'body' => 'Template caption, foto, dan video singkat.',
                'image' => 'star',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-get-2'
            ],
            [
                'title' => 'Pelaporan Penjualan',
                'body' => 'Dashboard sederhana untuk track komisi.',
                'image' => 'chart',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-get-3'
            ],
            [
                'title' => 'Pembayaran Cepat',
                'body' => 'Pembayaran komisi yang transparan dan rutin.',
                'image' => 'dollar',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-get-4'
            ],
            [
                'title' => 'Akses Promo',
                'body' => 'Diskon khusus & bundle eksklusif untuk mitra.',
                'image' => 'gift',
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-get-5'
            ],
            [
                'title' => 'Support Grup',
                'body' => 'Grup komunitas untuk bertukar ide dan tips.',
                'image' => 'users',
                'item_id' => null,
                'status' => true,
            ]
        );

        // Perfect For Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-perfect-for-title'
            ],
            [
                'title' => 'Perfect for you',
                'body' => '',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-perfect-1'
            ],
            [
                'title' => 'Moms',
                'body' => 'Cocok bagi ibu yang ingin berbagi produk parenting.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-perfect-2'
            ],
            [
                'title' => 'Content Creator',
                'body' => 'Buat konten dan monetize audiensmu.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-perfect-3'
            ],
            [
                'title' => 'WA/FB Seller',
                'body' => 'Tambahan produk untuk dipromosikan ke pelangganmu.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-perfect-4'
            ],
            [
                'title' => 'Reseller',
                'body' => 'Bisnis sampingan tanpa stok besar.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-perfect-5'
            ],
            [
                'title' => 'Community Leader',
                'body' => 'Bantu komunitasmu mendapatkan produk berkualitas.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        // Testimonial Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-testimonial-title'
            ],
            [
                'title' => 'Telah dipercaya oleh 30.000 Ibu',
                'body' => '',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-testimonial-1'
            ],
            [
                'title' => 'Ibu Siti',
                'body' => 'Menjadi mitra sangat mudah — dukungan tim cepat dan produknya dipercaya.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        // How to Join Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-how-to-join-title'
            ],
            [
                'title' => 'How to Join',
                'body' => '',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-step-1'
            ],
            [
                'title' => 'Daftar',
                'body' => 'Isi formulir singkat pendaftaran.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-step-2'
            ],
            [
                'title' => 'Dapat Materi',
                'body' => 'Terima materi promosi & panduan.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-step-3'
            ],
            [
                'title' => 'Promosikan',
                'body' => 'Bagikan link & kode affiliate.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'reseller',
                'section' => 'reseller-step-4'
            ],
            [
                'title' => 'Terima Komisi',
                'body' => 'Nikmati komisi dan insentif.',
                'image' => null,
                'item_id' => null,
                'status' => true,
            ]
        );
    }
}
