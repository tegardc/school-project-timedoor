<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolDetail;

class SchoolDetailSeeder extends Seeder
{
    public function run(): void
    {

        SchoolDetail::create([
            'name' => 'SMAN 1 Denpasar',
            'institutionCode' => 'INST001',
            'schoolId' => 1,
            'statusId' => 1,
            'educationLevelId' => 2,
            'ownershipStatus' => 'Negeri',
            'dateEstablishmentDecree' => '2000-01-01',
            'operationalLicense' => 'Izin-5678',
            'dateOperationalLicense' => '2001-01-01',
            'principal' => 'I Nyoman Sudarma',
            'operator' => 'Komang Yuni',
            'accreditationId' => 1,
            'curriculum' => 'Kurikulum Merdeka',
            'telpNo' => '0361-123456',
            'tuitionFee' => 200000,
            'numStudent' => 500,
            'numTeacher' => 50,
            'movie' => 'https://youtube.com/sample',
            'examInfo' => 'UNBK'
        ]);
    }
}
