<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterCompany;

class MasterCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterCompany::updateOrCreate(
            ['name_company' => 'Nyam Baby Food'],
            [
                'address_company' => 'Jl. Kapi Sraba Raya 12A 22, Desa Mangliawan, Kecamatan Pakis, Kab. Malang Jawa Timur, 65164',
                'phone_company' => '081234567891',
            ]
        );
        
        MasterCompany::updateOrCreate(
            ['name_company' => 'Mamina'],
            [
                'address_company' => 'Jl. Kapi Sraba Raya 12A 22, Desa Mangliawan, Kecamatan Pakis, Kab. Malang Jawa Timur, 65164',
                'phone_company' => '081234567890',
            ]
        );

        MasterCompany::updateOrCreate(
            ['name_company' => 'Gentle Living'],
            [
                'address_company' => 'Jl. Kapi Sraba Raya 12A 22, Desa Mangliawan, Kecamatan Pakis, Kab. Malang Jawa Timur, 65164',
                'phone_company' => '081234567892',
            ]
        );
    }
}
