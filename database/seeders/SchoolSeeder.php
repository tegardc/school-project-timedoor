<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {

        School::create([
            'name' => 'SMAN 1 Denpasar',
            'provinceId' => 1,
            'districtId' => 1,
            'subDistrictId' => 1,
            'schoolEstablishmentDecree' => 'SK-1234-DENPASAR'
        ]);
    }
}
