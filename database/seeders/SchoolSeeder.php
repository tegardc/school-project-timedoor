<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Data mentah dari JSON
        $schools = $this->getSchoolData();

        foreach ($schools as $data) {
            School::create([
                'name' => $data['nama_sekolah'],
                // Menggunakan SK Pendirian, jika kosong gunakan SK Izin, jika kosong strip
                'schoolEstablishmentDecree' => $data['sk_pendirian'] ?: ($data['sk_izin_operasional'] ?: '-'),
            ]);
        }
    }

    private function getSchoolData()
    {
        return [
            [
                "nama_sekolah" => "SMKN 2 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "-",
            ],
            [
                "nama_sekolah" => "SMKN 3 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "0311/0/1975",
            ],
            [
                "nama_sekolah" => "SMKS KERTHA WISATA DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "187/I.19/L.1/I.1991",
            ],
            [
                "nama_sekolah" => "SMKS PARIWISATA HARAPAN DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "2/I 19/Kep/I.89",
            ],
            [
                "nama_sekolah" => "SMKS PGRI 6 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/2537/DIKPORA",
            ],
            [
                "nama_sekolah" => "SMKS TEKNOLOGI NASIONAL",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/1015/Dikpora",
            ],
            [
                "nama_sekolah" => "SMKS TI BALI GLOBAL",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/3399/DIKBUD",
            ],
            [
                "nama_sekolah" => "SMK NEGERI 7 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "B.30.420/3699/IZIN-D/DPMPTSP",
            ],
            [
                "nama_sekolah" => "SMK Muhammadiyah Denpasar",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "420/202/IV-B/DISPMPT/2019",
            ],
            [
                "nama_sekolah" => "SMKS BINTANG PERSADA DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "2440/03-A/HK/2018",
            ],
            [
                "nama_sekolah" => "SMKS PGRI 2 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "B.31.420/30718/SMK/DIKPORA",
            ],
            [
                "nama_sekolah" => "SMKS PGRI 4 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "494/I.19.TI/MN/2000",
            ],
            [
                "nama_sekolah" => "SMKN 1 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "B.31.420/25465/SMK/DIKPORA",
            ],
            [
                "nama_sekolah" => "SMK SARASWATI 1 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "34567/D/4/75",
            ],
            [
                "nama_sekolah" => "SMKS  WIRA BHAKTI DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/1732/DIKPORA",
            ],
            [
                "nama_sekolah" => "SMKS BALI DEWATA",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/2600/Dikpora/2009",
            ],
            [
                "nama_sekolah" => "SMKS BINA MADINA",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421:3/10511/DIKPORA/2010",
            ],
            [
                "nama_sekolah" => "SMKS DUTA BANGSA",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/2538/Dikpora",
            ],
            [
                "nama_sekolah" => "SMKS DWIJENDRA DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "Nomor. 421.3/610/DIKBUD",
            ],
            [
                "nama_sekolah" => "SMKS FARMASI SARASWATI 3 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/1015/DIKPORA",
            ],
            [
                "nama_sekolah" => "SMKS KESEHATAN BALI DEWATA",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/4329.A/DIKPORA/2012",
            ],
            [
                "nama_sekolah" => "SMKS KESEHATAN BALI MEDIKA DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/1013/DIKPORA",
            ],
            [
                "nama_sekolah" => "SMKS Mild Bali",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "363/1/1/pp/E/B/1970",
            ],
            [
                "nama_sekolah" => "SMKS PGRI 1 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "2/I.19/Kep/I/1990-",
            ],
            [
                "nama_sekolah" => "SMKS REKAYASA DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "30/I19/Kep/I.87",
            ],
            [
                "nama_sekolah" => "SMKS TP 45 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "969/BAN-SM/SK/2019",
            ],
            [
                "nama_sekolah" => "SMK NEGERI 6 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "B.30.420/5774/IzinC/DPMPTSP",
            ],
            [
                "nama_sekolah" => "SMKN 4 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "239/B.3/Kedj",
            ],
            [
                "nama_sekolah" => "SMKN 5 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "0917/C4.3/MN/98",
            ],
            [
                "nama_sekolah" => "SMK PENERBANGAN CAKRA NUSANTARA DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/3991/Dikpora/2016.",
            ],
            [
                "nama_sekolah" => "SMKS KESEHATAN PGRI DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/4222/DIKPORA/2012",
            ],
            [
                "nama_sekolah" => "SMKS PGRI 3 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "38/I.19/H/MN/00",
            ],
            [
                "nama_sekolah" => "SMKS PGRI 5 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "188/1759/DIKBUD",
            ],
            [
                "nama_sekolah" => "SMKS SARASWATI 2 DENPASAR",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.5/4681/Disdikpora",
            ],
            [
                "nama_sekolah" => "SMKS TARUNA WARMADEWA",
                "sk_pendirian" => "",
                "sk_izin_operasional" => "421.3/1567/Dikpora/2010",
            ],
        ];
    }
}
