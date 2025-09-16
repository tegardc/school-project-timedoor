<?php

namespace Database\Seeders;

use App\Models\SchoolGallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'schoolDetailId' => 1,
                'imageUrl' => 'https://picsum.photos/seed/school1/600/400',
                'isCover' => true,
            ],
            [
                'schoolDetailId' => 2,
                'imageUrl' => 'https://picsum.photos/seed/school2/600/400',
                'isCover' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            SchoolGallery::create($gallery);
        }
}}

