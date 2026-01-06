<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterContent;

class PartnerContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Section - Main Title & Description
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'hero-title'
            ],
            [
                'title' => 'Join Our Baby Wellness Affiliate Program',
                'body' => 'Kami sedang membuka program affiliate partnership untuk 3 produk best-seller kami yang fokus pada wellness bunda & bayi.',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Why Join Us Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'why-join-title'
            ],
            [
                'title' => 'Why Join Us',
                'body' => 'Kami percaya produk ini sangat cocok untuk audience kami yang didominasi moms, new parents, breastfeeding moms, dan pejuang MPASI. Helping Moms - Earning with Purpose',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Banner Carousel Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'carousel-1'
            ],
            [
                'title' => 'Mamina ASI Booster',
                'body' => 'herbal booster ASI alami, tanpa efek samping dan pemanis perisa',
                'image' => 'images/partner/carousel-1.jpg',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'carousel-2'
            ],
            [
                'title' => 'Baby Wellness Package',
                'body' => 'Paket lengkap perawatan kesehatan bayi untuk tumbuh kembang optimal',
                'image' => 'images/partner/carousel-2.jpg',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'carousel-3'
            ],
            [
                'title' => 'Nutrition MPASI',
                'body' => 'Nutrisi tambahan untuk MPASI yang kaya vitamin dan mineral penting',
                'image' => 'images/partner/carousel-3.jpg',
                'item_id' => null,
            ]
        );

        // Benefits
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'benefit-1'
            ],
            [
                'title' => 'Kebutuhan bunda dan baby',
                'body' => 'Berdasarkan data yang kami punya, produk kami salah satu kebutuhan si Kecil',
                'image' => 'check-badge',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'benefit-2'
            ],
            [
                'title' => 'Repeat order tinggi',
                'body' => 'Hasil dirasakan cepat & kebutuhan harian',
                'image' => 'shopping-cart',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'benefit-3'
            ],
            [
                'title' => 'Full support + edukasi',
                'body' => 'Kamu bisa dibantu berkembang secara skill atau kebutuhan konten',
                'image' => 'document-text',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'benefit-4'
            ],
            [
                'title' => 'Produk kategori premium',
                'body' => 'Bernilai jual cukup tinggi sehingga komisi besar untuk setiap penjualannya',
                'image' => 'star',
                'item_id' => null,
            ]
        );

        // What You Will Get Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'what-you-get-title'
            ],
            [
                'title' => 'What you will get',
                'body' => '',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'what-get-1'
            ],
            [
                'title' => 'Setiap penjualan produk mendapat komisi sebesar 12-15%',
                'body' => '',
                'image' => 'currency-dollar',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'what-get-2'
            ],
            [
                'title' => 'Tim support untuk bantu tracking & pelaporan hingga pengembangan skill',
                'body' => '',
                'image' => 'user-group',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'what-get-3'
            ],
            [
                'title' => 'Produk gratis untuk review (bisa 1-3 item)',
                'body' => '',
                'image' => 'gift',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'what-get-4'
            ],
            [
                'title' => 'Bonus bulanan dan tahunan untuk penjualan terbanyak',
                'body' => '',
                'image' => 'banknotes',
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'what-get-5'
            ],
            [
                'title' => 'Akses ke media kit (foto, video, script)',
                'body' => '',
                'image' => 'photo',
                'item_id' => null,
            ]
        );

        // Perfect For You Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'perfect-for-title'
            ],
            [
                'title' => 'Perfect for you',
                'body' => '',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'perfect-for-1'
            ],
            [
                'title' => 'Momfluencer',
                'body' => 'Sering berbagi daily life bermama anak',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'perfect-for-2'
            ],
            [
                'title' => 'Bidan/Educator MPASI',
                'body' => 'Aktif memberikan edukasi tentang tumbuh kembang dan nutrisi',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'perfect-for-3'
            ],
            [
                'title' => 'Admin Komunitas Ibu',
                'body' => 'Mengelola komunitas diskusi seputar parenting',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'perfect-for-4'
            ],
            [
                'title' => 'Content Creator Parenting',
                'body' => 'Membuat konten tips, review produk bayi, hingga edukasi',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'perfect-for-5'
            ],
            [
                'title' => 'Ibu Aktif',
                'body' => 'Suka berbagi info bermanfaat ke sesama ibu',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Testimonial Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'testimonial-title'
            ],
            [
                'title' => 'Telah dipercaya oleh 30.000 Ibu',
                'body' => '',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'testimonial-1'
            ],
            [
                'title' => 'Ibu Sarah',
                'body' => 'Produk-produknya benar-benar berkualitas dan sesuai dengan kebutuhan bayi. Sebagai affiliate, saya merasa bangga merekomendasikan produk yang saya gunakan sendiri untuk anak saya.',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'testimonial-2'
            ],
            [
                'title' => 'Bunda Dina',
                'body' => 'Alhamdulillah bergabung sebagai affiliate partner sangat mudah dan menguntungkan. Tim supportnya sangat membantu dan produknya laku keras di komunitas saya.',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'testimonial-3'
            ],
            [
                'title' => 'Mama Ira',
                'body' => 'Program affiliate partner ini benar-benar memberikan passive income yang konsisten. Produknya berkualitas dan customer selalu repeat order.',
                'image' => null,
                'item_id' => null,
            ]
        );

        // How to Join Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'how-to-join-title'
            ],
            [
                'title' => 'How to Join',
                'body' => 'Work Easy, Earn More - Kami bisa bantu rekomendasikan bikin konten/script sesuai gaya kamu',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'step-1'
            ],
            [
                'title' => 'Klik "DAFTAR SEKARANG" dan isi identitas diri',
                'body' => '',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'step-2'
            ],
            [
                'title' => 'Tim kami akan kirim:',
                'body' => 'Link affiliate, Product Knowledge, Brief konten & panduan',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'step-3'
            ],
            [
                'title' => 'Kamu tinggal share link affiliate ke IG Story, TikTok, WA Grup, atau komunitas Ibu',
                'body' => '',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'step-4'
            ],
            [
                'title' => 'Penjualan dan komisi dicatat otomatis lewat platform',
                'body' => '(atau Google Sheet kalau manual)',
                'image' => null,
                'item_id' => null,
            ]
        );

        // TikTok Videos Section
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'video-1'
            ],
            [
                'title' => 'Review Produk Mamina',
                'body' => null,
                'video_url' => 'https://www.tiktok.com/@mamatata387/video/7549952195060337940',
                'username' => 'mamatata387',
                'image' => null,
                'item_id' => null,
                'status' => 1,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'video-2'
            ],
            [
                'title' => 'Review Gentle Baby',
                'body' => null,
                'video_url' => 'https://www.tiktok.com/@deadisaa/video/7529849813773372690',
                'username' => 'deadisaa',
                'image' => null,
                'item_id' => null,
                'status' => 1,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'video-3'
            ],
            [
                'title' => 'Review Roll On Aromaterapi',
                'body' => null,
                'video_url' => 'https://www.tiktok.com/@deadisaa/video/7524316069671013650',
                'username' => 'deadisaa',
                'image' => null,
                'item_id' => null,
                'status' => 1,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'partner',
                'section' => 'video-4'
            ],
            [
                'title' => 'Solusi Baby Sleep',
                'body' => null,
                'video_url' => 'https://www.tiktok.com/@indahaprianto3/video/7549922000341355782',
                'username' => 'indahaprianto3',
                'image' => null,
                'item_id' => null,
                'status' => 1,
            ]
        );
    }
}
