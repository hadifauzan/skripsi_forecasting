<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterContent;

class HomepageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Banner Utama
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'banner'
            ],
            [
                'title' => 'Therapeutic Baby Massage Oil',
                'body' => 'Minyak Bayi Aromaterapi, kombinasi Essential Oil dan Sunflower Seed Oil untuk kesehatan ibu, bayi dan balita. Khasiat sama dengan kemasan lebih ekonomis',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Produk Banner dengan 4 point
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'banner-product'
            ],
            [
                'title' => 'Gentle Baby',
                'body' => json_encode([
                    'points' => [
                        '100% alami',
                        'BPOM Certified',
                        'Newborn Friendly',
                        'Teruji Klinis'
                    ]
                ]),
                'image' => null,
                'item_id' => null,
            ]
        );

        // Information Section - Main (Bagian Atas)
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'information-main'
            ],
            [
                'title' => 'Lebih dari Sekedar Produk, Ini Bentuk Kasih Sayang',
                'body' => 'Di setiap hal kecil yang kami lakukan, kami tuangkan cinta dan kasih sayang yang begitu mendalam. Bukan sekedar memenuhi kebutuhan, tapi juga menjadi jembatan dalam membangun ikatan penuh cinta antara ibu dan anak.',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Information Section - Grid 1 (Terpercaya)
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'information-1'
            ],
            [
                'title' => 'Terpercaya',
                'body' => 'Produk bergaransi tinggi yang telah teruji dan direkomendasikan ahli gizi untuk tumbuh kembang optimal si kecil',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Information Section - Grid 2 (Konsultasi Gratis)
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'information-2'
            ],
            [
                'title' => 'Konsultasi Gratis',
                'body' => 'Layanan konsultasi langsung dengan ahli untuk membantu perjalanan menyusui dan tumbuh kembang anak Anda',
                'image' => null,
                'item_id' => null,
            ]
        );

        // Information Section - Grid 3 (BPOM Certified)
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'information-3'
            ],
            [
                'title' => 'BPOM Certified',
                'body' => 'Tersertifikasi oleh BPOM, memastikan produk bebas dari bahan berbahaya dan aman untuk bayi maupun anak',
                'image' => null,
                'item_id' => null,
            ]
        );


        // FAQ Section - Sample FAQs
        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'faq-1'
            ],
            [
                'title' => 'Apakah produk Gentle Baby aman untuk bayi yang baru lahir?',
                'body' => 'Ya, produk Gentle Baby diformulasikan khusus untuk bayi dari usia 0 bulan. Menggunakan 100% bahan alami yang aman.',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'faq-2'
            ],
            [
                'title' => 'Apakah Nyam BB Booster mengandung pengawet?',
                'body' => 'Tidak, semua produk Nyam dibuat tanpa pengawet buatan. Kami menggunakan bahan alami untuk menjaga kualitas dan kesegaran produk.',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'faq-3'
            ],
            [
                'title' => 'Bagaimana cara menggunakan Mamina ASI Booster?',
                'body' => 'Seduh 1 sachet Mamina dengan 200ml air hangat, aduk rata dan minum 2x sehari. Konsumsi secara rutin untuk hasil yang optimal.',
                'image' => null,
                'item_id' => null,
            ]
        );

        MasterContent::updateOrCreate(
            [
                'type_of_page' => 'homepage',
                'section' => 'faq-4'
            ],
            [
                'title' => 'Apakah produk sudah memiliki sertifikat BPOM?',
                'body' => 'Ya, semua produk kami telah tersertifikasi BPOM dan telah lolos uji keamanan sehingga aman untuk dikonsumsi.',
                'image' => null,
                'item_id' => null,
            ]
        );

        $this->command->info('Homepage content seeded successfully!');
    }
}
