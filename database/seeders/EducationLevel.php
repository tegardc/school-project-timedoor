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
        ModelsEducationLevel::create(['name' => 'SD']);
        ModelsEducationLevel::create(['name' => 'SMP']);
        ModelsEducationLevel::create(['name' => 'SMA']);
        ModelsEducationLevel::create(['name' => 'SMK']);
        ModelsEducationLevel::create(['name' => 'Universitas']);
    }
}
