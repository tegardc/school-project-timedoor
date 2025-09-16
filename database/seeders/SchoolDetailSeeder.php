<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolDetail;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class SchoolDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $schools = [
                [
                    'name' => 'SDN 1 Kuta',
                    'institutionCode' => 'INST0001',
                    'schoolId' => 1,
                    'statusId' => 1,
                    'educationLevelId' => 1,
                    'educationProgramId' => 1,
                    'ownershipStatus' => 'Negeri',
                    'dateEstablishmentDecree' => '2000-01-01',
                    'operationalLicense' => 'IZIN-001',
                    'dateOperationalLicense' => '2001-01-01',
                    'principal' => 'Ketut Putra',
                    'operator' => 'Made Dewi',
                    'accreditationId' => 1,
                    'curriculum' => 'K13',
                    'tuitionFee' => 0,
                    'numStudent' => 300,
                    'numTeacher' => 25,
                    'movie' => 'https://youtube.com/dummy1',
                    'examInfo' => 'UNBK',
                ],
                [
                    'name' => 'SMPN 2 Kuta',
                    'institutionCode' => 'INST0002',
                    'schoolId' => 2,
                    'statusId' => 2,
                    'educationLevelId' => 2,
                    'educationProgramId' => 1,
                    'ownershipStatus' => 'Swasta',
                    'dateEstablishmentDecree' => '2005-02-01',
                    'operationalLicense' => 'IZIN-002',
                    'dateOperationalLicense' => '2006-01-01',
                    'principal' => 'Ni Luh Sari',
                    'operator' => 'Komang Gede',
                    'accreditationId' => 2,
                    'curriculum' => 'Kurikulum Merdeka',
                    'tuitionFee' => 150000,
                    'numStudent' => 400,
                    'numTeacher' => 30,
                    'movie' => 'https://youtube.com/dummy2',
                    'examInfo' => 'UNBK',
                ],
                // 👉 lanjutkan data lain sama pola ini...
            ];

            foreach ($schools as $data) {
                $address = Address::create([
                    'provinceId'   => 1,
                    'districtId'   => 1,
                    'subdistrictId'=> 1,
                    'street'       => 'Jl. Raya Kuta No. 1',
                    'postalCode'   => '80361',
                    'latitude'     => '-8.7237',
                    'longitude'    => '115.1767',
                ]);

                SchoolDetail::create(array_merge($data, [
                    'addressId'     => $address->id, // ✅ foreign key
                ]));
            }
        });
    }
}
