<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AffiliateRegistration;
use App\Models\AffiliateInfo;

class AffiliateWithSocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data with social media
        $affiliates = [
            [
                'email' => 'sarah.beautyblog@gmail.com',
                'nama_lengkap' => 'Sarah Wijaya',
                'kontak_whatsapp' => '08123456789',
                'kota_domisili' => 'Jakarta',
                'profesi_kesibukan' => 'Beauty Blogger & Content Creator',
                'info_darimana' => 'Instagram',
                'status' => 'Aktif',
                'social_media' => [
                    'akun_instagram' => '@sarah.beautyblog',
                    'akun_tiktok' => '@sarahwbeauty'
                ]
            ],
            [
                'email' => 'maya.momlifestyle@gmail.com',
                'nama_lengkap' => 'Maya Sari',
                'kontak_whatsapp' => '08234567890',
                'kota_domisili' => 'Bandung',
                'profesi_kesibukan' => 'Ibu Rumah Tangga & Mom Influencer',
                'info_darimana' => 'TikTok',
                'status' => 'Aktif',
                'social_media' => [
                    'akun_instagram' => '@maya.momlife',
                    'akun_tiktok' => null
                ]
            ],
            [
                'email' => 'linda.healthylife@gmail.com',
                'nama_lengkap' => 'Linda Putri',
                'kontak_whatsapp' => '08345678901',
                'kota_domisili' => 'Surabaya',
                'profesi_kesibukan' => 'Nutritionist & Health Coach',
                'info_darimana' => 'Teman',
                'status' => 'Aktif',
                'social_media' => [
                    'akun_instagram' => null,
                    'akun_tiktok' => '@linda.healthylife'
                ]
            ],
            [
                'email' => 'rina.fashiongram@gmail.com',
                'nama_lengkap' => 'Rina Mahardika',
                'kontak_whatsapp' => '08456789012',
                'kota_domisili' => 'Yogyakarta',
                'profesi_kesibukan' => 'Fashion Designer & Social Media Influencer',
                'info_darimana' => 'Instagram',
                'status' => 'Aktif',
                'social_media' => [
                    'akun_instagram' => '@rina.fashiongram',
                    'akun_tiktok' => '@rinafashion'
                ]
            ]
        ];

        foreach ($affiliates as $affiliateData) {
            // Check if affiliate already exists
            $existingAffiliate = AffiliateRegistration::where('email', $affiliateData['email'])->first();
            
            if (!$existingAffiliate) {
                // Create affiliate registration
                $affiliate = AffiliateRegistration::create([
                    'email' => $affiliateData['email'],
                    'nama_lengkap' => $affiliateData['nama_lengkap'],
                    'kontak_whatsapp' => $affiliateData['kontak_whatsapp'],
                    'kota_domisili' => $affiliateData['kota_domisili'],
                    'profesi_kesibukan' => $affiliateData['profesi_kesibukan'],
                    'info_darimana' => $affiliateData['info_darimana'],
                    'status' => $affiliateData['status']
                ]);

                // Create affiliate info with social media
                AffiliateInfo::create([
                    'affiliate_registration_id' => $affiliate->id,
                    'akun_instagram' => $affiliateData['social_media']['akun_instagram'],
                    'akun_tiktok' => $affiliateData['social_media']['akun_tiktok']
                ]);
            }
        }

        $this->command->info('Successfully created ' . count($affiliates) . ' affiliates with social media data!');
    }
}
