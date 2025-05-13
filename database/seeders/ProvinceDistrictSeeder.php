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
            'province_id' => $bali->id
        ]);

        $denpasar = District::create([
            'name' => 'Kota Denpasar',
            'province_id' => $bali->id
        ]);

        // Sub-districts (kecamatan) di Kabupaten Badung
        SubDistrict::create([
            'name' => 'Kuta',
            'district_id' => $badung->id
        ]);

        SubDistrict::create([
            'name' => 'Mengwi',
            'district_id' => $badung->id
        ]);

        // Sub-districts (kecamatan) di Kota Denpasar
        SubDistrict::create([
            'name' => 'Denpasar Barat',
            'district_id' => $denpasar->id
        ]);

        SubDistrict::create([
            'name' => 'Denpasar Timur',
            'district_id' => $denpasar->id
        ]);
    }
}
