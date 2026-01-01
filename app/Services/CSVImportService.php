<?php

namespace App\Services;

use App\Models\{
    Province,
    District,
    SubDistrict,
    School,
    SchoolDetail,
    SchoolStatus,
    EducationLevel,
    Accreditation,
    Address,
    SchoolGallery,
    Contact,
    Facility
};
use Illuminate\Support\Facades\DB;

class CSVImportService
{
    private function parseDate($date)
    {
        if (empty($date)) return null;
        $parsed = \DateTime::createFromFormat('d/m/Y', $date);
        if (!$parsed) {
            $parsed = \DateTime::createFromFormat('m/d/Y', $date);
        }

        return $parsed ? $parsed->format('Y-m-d') : null;
    }
    public function import(array $data)
    {
        DB::beginTransaction();

        try {
            foreach ($data as $item) {
                $province = Province::firstOrCreate(['name' => $item['provinsi']]);
                $district = District::firstOrCreate(['name' => $item['kabupaten'], 'provinceId' => $province->id]);
                $subdistrict = SubDistrict::firstOrCreate(['name' => $item['kecamatan'], 'districtId' => $district->id]);

                $status = SchoolStatus::firstOrCreate(['name' => $item['status']]);
                $educationLevel = EducationLevel::firstOrCreate(['name' => $item['bentuk_pendidikan']]);
                $accreditation = Accreditation::firstOrCreate(['code' => $item['akreditasi']]);

                $school = School::updateOrCreate(
                    ['name' => $item['nama_sekolah']],
                    [
                        'schoolEstablishmentDecree' => $item['sk_pendirian'] ?? null,
                    ]
                );
                $address = Address::create([
                    'provinceId' => $province->id,
                    'districtId' => $district->id,
                    'subDistrictId' => $subdistrict->id,
                    'village' => $item['desa'] ?? null,
                    'street' => $item['alamat'] ?? null,
                    'postalCode' => $item['kode_pos'] ?? null,
                    'latitude' => $item['lintang'] ?? null,
                    'longitude' => $item['bujur'] ?? null,
                ]);

                $schoolDetail = SchoolDetail::create([
                    'schoolId' => $school->id,
                    'name' => $item['nama_sekolah'],
                    'institutionCode' => $item['npsn'],
                    'statusId' => $status->id,
                    'educationLevelId' => $educationLevel->id,
                    'accreditationId' => $accreditation->id,
                    'ownershipStatus' => $item['status_kepemilikan'] ?? null,
                    'dateEstablishmentDecree' => $this->parseDate($item['tanggal_sk_pendirian']) ?? null,
                    'dateOperationalLicense' => $this->parseDate($item['tanggal_sk_izin_operasional']) ?? null,
                    'operationalLicense' => $item['sk_izin_operasional'] ?? null,
                    'principal' => $item['kepsek'] ?? null,
                    'operator' => $item['operator'] ?? null,
                    'curriculum' => $item['kurikulum'] ?? null,
                    'telpNo' => $item['telephone_number'] ?? null,
                    'tuitionFee' => $item['tution_fee'] ?? null,
                    'numStudent' => $item['num_of_students'] ?? null,
                    'numTeacher' => $item['num_of_teacher'] ?? null,
                    'movie' => $item['video'] ?? null,
                    'addressId' => $address->id

                ]);

                // EMAIL
                if (!empty($item['email'])) {
                    Contact::create([
                        'schoolDetailId' => $schoolDetail->id,
                        'type' => 'email',
                        'value' => $item['email'],
                    ]);
                }

                // WEBSITE
                if (!empty($item['website'])) {
                    Contact::create([
                        'schoolDetailId' => $schoolDetail->id,
                        'type' => 'website',
                        'value' => $item['website'],
                    ]);
                }

                if (!empty($item['gambar'])) {
                    SchoolGallery::create([
                        'schoolDetailId' => $schoolDetail->id,
                        'imageUrl' => $item['gambar'],
                    ]);
                }
                // FACILITIES
                if (!empty($item['fasilitas'])) {

                    // split "Toilet|Perpustakaan|Laboratorium"
                    $facilityNames = explode('|', $item['fasilitas']);

                    foreach ($facilityNames as $facilityName) {
                        $facilityName = trim($facilityName);

                        if ($facilityName === '') continue;

                        // create / get facility
                        $facility = Facility::firstOrCreate(
                            ['name' => $facilityName],
                            [
                                'createdAt' => now(),
                                'updatedAt' => now(),
                            ]
                        );

                        // attach ke pivot (hindari duplicate)
                        $schoolDetail->facilities()->syncWithoutDetaching([
                            $facility->id => [
                                'createdAt' => now(),
                                'updatedAt' => now(),
                            ]
                        ]);
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function preview($file): array
    {
        $csvData = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $csvData;
    }
}
