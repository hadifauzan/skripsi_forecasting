<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterContent;

class AboutUsBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create hero content (title & body)
        MasterContent::updateOrCreate([
            'type_of_page' => 'about_us',
            'section' => 'hero'
        ], [
            'title' => 'Tentang Kami',
            'body' => 'Mengenal lebih dekat perjalanan Gentle Living dalam menghadirkan nutrisi terbaik dan produk berkualitas untuk keluarga Indonesia',
            'image' => null
        ]);

        // Create 3 banner placeholders for About Us
        for ($i = 1; $i <= 3; $i++) {
            MasterContent::updateOrCreate([
                'type_of_page' => 'about_us',
                'section' => "banner-{$i}"
            ], [
                'title' => "Banner {$i}",
                'body' => 'About Us Banner Image',
                'image' => null
            ]);
        }

        // Create other About Us content sections
        MasterContent::updateOrCreate([
            'type_of_page' => 'about_us',
            'section' => 'about'
        ], [
            'title' => 'Tentang Kami',
            'body' => 'Gentle Living adalah perusahaan yang berkomitmen menyediakan produk MPASI (Makanan Pendamping ASI) berkualitas tinggi untuk mendukung tumbuh kembang optimal si kecil.',
            'image' => null
        ]);

        MasterContent::updateOrCreate([
            'type_of_page' => 'about_us',
            'section' => 'journey'
        ], [
            'title' => 'Perjalanan Kami',
            'body' => 'Dimulai dari visi sederhana untuk memberikan akses mudah terhadap produk berkualitas, kami terus berkembang melayani kebutuhan pelanggan.',
            'image' => null
        ]);

        MasterContent::updateOrCreate([
            'type_of_page' => 'about_us',
            'section' => 'vision-mission'
        ], [
            'title' => 'Visi & Misi',
            'body' => 'Visi kami adalah menjadi platform terdepan dalam menyediakan produk berkualitas. Misi kami adalah memberikan pelayanan terbaik kepada setiap pelanggan.',
            'image' => null
        ]);
    }
}
