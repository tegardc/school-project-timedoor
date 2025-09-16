<?php

namespace Database\Seeders;

use App\Models\EducationProgram as ModelsEducationProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationProgram extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsEducationProgram::create([
            'name' => 'Sastra Indonesia',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsEducationProgram::create([
            'name' => 'Sastra Jepang',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsEducationProgram::create([
            'name' => 'Sastra Inggris',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        //
    }
}
