<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;
use App\Models\SubDistrict;

class ProvinceDistrictSeeder extends Seeder
{
    public function run()
    {
        // Contoh: Provinsi Bali
        $bali = Province::create([
            'name' => 'Bali',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Districts di Bali
        $badung = District::create([
            'name' => 'Kabupaten Badung',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        $denpasar = District::create([
            'name' => 'Kota Denpasar',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Sub-districts (kecamatan) di Kabupaten Badung
        SubDistrict::create([
            'name' => 'Kuta',
            'districtId' => $badung->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        SubDistrict::create([
            'name' => 'Mengwi',
            'districtId' => $badung->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Sub-districts (kecamatan) di Kota Denpasar
        SubDistrict::create([
            'name' => 'Denpasar Barat',
            'districtId' => $denpasar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        SubDistrict::create([
            'name' => 'Denpasar Timur',
            'districtId' => $denpasar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
    }
}
