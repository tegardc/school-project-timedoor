<?php

namespace Database\Seeders;

use App\Models\EducationLevel as ModelsEducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationLevel extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsEducationLevel::create([
            'name' => 'SD',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsEducationLevel::create([
            'name' => 'SMP',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsEducationLevel::create([
            'name' => 'SMA',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsEducationLevel::create([
            'name' => 'SMK',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsEducationLevel::create([
            'name' => 'Universitas',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
    }
}
