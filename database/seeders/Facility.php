<?php

namespace Database\Seeders;

use App\Models\Facility as ModelsFacility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Facility extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = ModelsFacility::create([
            'name' => 'Toilet',
            'image' => '',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $facilities = ModelsFacility::create([
            'name' => 'Perpustakaan',
            'image' => '',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $facilities = ModelsFacility::create([
            'name' => 'Laboratorium',
            'image' => '',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $facilities = ModelsFacility::create([
            'name' => 'Aula',
            'image' => '',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $facilities = ModelsFacility::create([
            'name' => 'Gedung Olahraga',
            'image' => '',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        //
    }
}
