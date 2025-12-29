<?php

namespace Database\Seeders;

use App\Models\SchoolGallery;
use Illuminate\Database\Seeder;

class SchoolGallerySeeder extends Seeder
{
    public function run(): void
    {
        // Data gambar dari JSON (Hanya diambil yang memiliki link gambar)
        $galleries = [
            ['schoolDetailId' => 1, 'imageUrl' => 'https://live.staticflickr.com/8647/16667094562_47630b18e9_b.jpg'],
            ['schoolDetailId' => 2, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103116/3.jpg'],
            ['schoolDetailId' => 3, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103643/1.jpg'],
            ['schoolDetailId' => 4, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103911/1.jpg'],
            ['schoolDetailId' => 5, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103171/3.jpg'],
            ['schoolDetailId' => 6, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103911/2.jpg'],
            ['schoolDetailId' => 7, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/69959273/1.jpg'],
            ['schoolDetailId' => 8, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103120/3.jpg'],
            ['schoolDetailId' => 9, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103178/1.jpg'],
            ['schoolDetailId' => 10, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50105467/2.jpg'],
            ['schoolDetailId' => 11, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103112/2.jpg'],
            ['schoolDetailId' => 12, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103129/6.jpg'],
            ['schoolDetailId' => 13, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103118/4.jpg'],
            ['schoolDetailId' => 14, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103255/1.jpg'],
            ['schoolDetailId' => 15, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103907/6.jpg'],
            ['schoolDetailId' => 16, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/69765015/2.jpg'],
            ['schoolDetailId' => 17, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50105447/2.jpg'],
            ['schoolDetailId' => 18, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/30401515/1.jpg'],
            ['schoolDetailId' => 19, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103833/1.jpg'],
            ['schoolDetailId' => 20, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103633/3.jpg'],
            ['schoolDetailId' => 21, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/69765015/2.jpg'],
            ['schoolDetailId' => 22, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103910/2.jpg'],
            ['schoolDetailId' => 23, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50104192/6.jpg'],
            ['schoolDetailId' => 24, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103113/1.jpg'],
            ['schoolDetailId' => 25, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/20504484/1.jpg'],
            ['schoolDetailId' => 26, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103145/3.jpg'],
            ['schoolDetailId' => 27, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103121/3.jpg'],
            ['schoolDetailId' => 28, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103638/5.jpg'],
            ['schoolDetailId' => 29, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103638/5.jpg'],
            ['schoolDetailId' => 30, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/69948489/1.jpg'],
            ['schoolDetailId' => 31, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/69759223/2.jpg'],
            ['schoolDetailId' => 32, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/69759223/2.jpg'],
            ['schoolDetailId' => 33, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103638/5.jpg'],
            ['schoolDetailId' => 34, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50103147/3.jpg'],
            ['schoolDetailId' => 35, 'imageUrl' => 'https://cdn-sekolah.annibuku.com/50105493/2.jpg'],
        ];

        foreach ($galleries as $gallery) {
            SchoolGallery::create([
                'schoolDetailId' => $gallery['schoolDetailId'],
                'imageUrl'       => $gallery['imageUrl'],
                'isCover'        => true,
            ]);
        }
    }
}
