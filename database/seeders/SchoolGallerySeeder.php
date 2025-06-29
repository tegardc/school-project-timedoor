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
                'schoolId' => 1,
                'schoolDetailId' => 1,
                'imageUrl' => 'https://picsum.photos/seed/school1/600/400',
                'isCover' => true,
            ],
            [
                'schoolId' => 1,
                'schoolDetailId' => 1,
                'imageUrl' => 'https://picsum.photos/seed/school1b/600/400',
                'isCover' => false,
            ],
            [
                'schoolId' => 2,
                'schoolDetailId' => 2,
                'imageUrl' => 'https://picsum.photos/seed/school2/600/400',
                'isCover' => true,
            ],
            [
                'schoolId' => 3,
                'schoolDetailId' => 3,
                'imageUrl' => 'https://picsum.photos/seed/school3/600/400',
                'isCover' => true,
            ],
            [
                'schoolId' => 3,
                'schoolDetailId' => 3,
                'imageUrl' => 'https://picsum.photos/seed/school3b/600/400',
                'isCover' => false,
            ],

        ];

        foreach ($galleries as $gallery) {
            SchoolGallery::create($gallery);
        }
}}

