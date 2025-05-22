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
        $bali = Province::create(['name' => 'Bali']);

        // Districts di Bali
        $badung = District::create([
            'name' => 'Kabupaten Badung',
            'provinceId' => $bali->id
        ]);

        $denpasar = District::create([
            'name' => 'Kota Denpasar',
            'provinceId' => $bali->id
        ]);

        // Sub-districts (kecamatan) di Kabupaten Badung
        SubDistrict::create([
            'name' => 'Kuta',
            'districtId' => $badung->id
        ]);

        SubDistrict::create([
            'name' => 'Mengwi',
            'districtId' => $badung->id
        ]);

        // Sub-districts (kecamatan) di Kota Denpasar
        SubDistrict::create([
            'name' => 'Denpasar Barat',
            'districtId' => $denpasar->id
        ]);

        SubDistrict::create([
            'name' => 'Denpasar Timur',
            'districtId' => $denpasar->id
        ]);
    }
}
