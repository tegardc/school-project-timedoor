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
            'name' => 'Badung',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        $denpasar = District::create([
            'name' => 'Denpasar',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $bangli = District::create([
            'name' => 'Bangli',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $buleleng = District::create([
            'name' => 'Buleleng',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $gianyar = District::create([
            'name' => 'Gianyar',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $jembrana = District::create([
            'name' => 'Jembrana',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        $karangasem = District::create([
            'name' => 'Karangasem',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        $klungkung = District::create([
            'name' => 'Klungkung',
            'provinceId' => $bali->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        $tabanan = District::create([
            'name' => 'Tabanan',
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
        SubDistrict::create([
            'name' => 'Kuta Utara',
            'districtId' => $badung->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Kuta Selatan',
            'districtId' => $badung->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Petang',
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
        SubDistrict::create([
            'name' => 'Denpasar Utara',
            'districtId' => $denpasar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Denpasar Selatan',
            'districtId' => $denpasar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Sub-districts (kecamatan) di Kabupaten Bangli
        SubDistrict::create([
            'name' => 'Bangli',
            'districtId' => $bangli->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Susut',
            'districtId' => $bangli->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Tembuku',
            'districtId' => $bangli->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Kintamani',
            'districtId' => $bangli->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // sub-districts di buleleng
        SubDistrict::create([
            'name'=>'Gerokgak',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Seririt',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Busungbiu',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Banjar',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Sukasada',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Buleleng',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Sawan',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Kubutambahan',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);
        SubDistrict::create([
            'name'=>'Tejakula',
            'districtId'=>$buleleng->id,
            'createdAt'=>now(),
            'updatedAt'=>now(),
        ]);

        // Sub-districts (kecamatan) di Kabupaten Gianyar
        SubDistrict::create([
            'name' => 'Blahbatuh',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Gianyar',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Payangan',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Sukawati',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Tampaksiring',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Tegallalang',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Ubud',
            'districtId' => $gianyar->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Sub-districts (kecamatan) di Kabupaten Jembrana
        SubDistrict::create([
            'name' => 'Melaya',
            'districtId' => $jembrana->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Negara',
            'districtId' => $jembrana->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Jembrana',
            'districtId' => $jembrana->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Mendoyo',
            'districtId' => $jembrana->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Pekutatan',
            'districtId' => $jembrana->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Sub-districts (kecamatan) di Kabupaten Karangasem
        SubDistrict::create([
            'name' => 'Abang',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Babandem',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Karangasem',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Kubu',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Manggis',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Rendang',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Selat',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        SubDistrict::create([
            'name' => 'Sidemen',
            'districtId' => $karangasem->id,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // Sub-districts Klungkung
        SubDistrict::create([
            'name' => 'Nusapenida',
            'districtId' => $klungkung->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Banjarangkan',
            'districtId' => $klungkung->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Klungkung',
            'districtId' => $klungkung->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Dawan',
            'districtId' => $klungkung->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);

        // Sub-districts Tabanan
        SubDistrict::create([
            'name' => 'Selemadeg',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Selemadeg Timur',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Selemadeg Barat',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Kerambitan',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Tabanan',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Kediri',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Marga',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Penebel',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Baturiti',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
        SubDistrict::create([
            'name' => 'Pupuan',
            'districtId' => $tabanan->id,
            'createdAt'=>now(),
            'updatedAt'=>now()
        ]);
    }
}
