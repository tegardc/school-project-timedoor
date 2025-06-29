<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {

        School::insert([
            [
                'name' => 'SDN 1 Kuta',
                'provinceId' => 1,
                'districtId' => 1,
                'subDistrictId' => 1,
                'schoolEstablishmentDecree' => 'SK-001-KUTA',
            ],
            [
                'name' => 'SMPN 2 Kuta',
                'provinceId' => 1,
                'districtId' => 1,
                'subDistrictId' => 1,
                'schoolEstablishmentDecree' => 'SK-002-KUTA',
            ],
            [
                'name' => 'SDN 3 Mengwi',
                'provinceId' => 1,
                'districtId' => 1,
                'subDistrictId' => 2,
                'schoolEstablishmentDecree' => 'SK-003-MENGWI',
            ],
            [
                'name' => 'SMAN 1 Mengwi',
                'provinceId' => 1,
                'districtId' => 1,
                'subDistrictId' => 2,
                'schoolEstablishmentDecree' => 'SK-004-MENGWI',
            ],
            [
                'name' => 'SDN 4 Denpasar Barat',
                'provinceId' => 1,
                'districtId' => 2,
                'subDistrictId' => 3,
                'schoolEstablishmentDecree' => 'SK-005-DPB',
            ],
            [
                'name' => 'SMPN 1 Denpasar Barat',
                'provinceId' => 1,
                'districtId' => 2,
                'subDistrictId' => 3,
                'schoolEstablishmentDecree' => 'SK-006-DPB',
            ],
            [
                'name' => 'SMAN 2 Denpasar Timur',
                'provinceId' => 1,
                'districtId' => 2,
                'subDistrictId' => 4,
                'schoolEstablishmentDecree' => 'SK-007-DPT',
            ],
            [
                'name' => 'SMKN 1 Denpasar Timur',
                'provinceId' => 1,
                'districtId' => 2,
                'subDistrictId' => 4,
                'schoolEstablishmentDecree' => 'SK-008-DPT',
            ],
        ]);
    }
}
