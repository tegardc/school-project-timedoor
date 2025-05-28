<?php

namespace Database\Seeders;

use App\Models\Accreditation as ModelsAccreditation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Accreditation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsAccreditation::create([
            'code' => 'Unggulan',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsAccreditation::create([
            'code' => 'A',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsAccreditation::create([
            'code' => 'B',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        ModelsAccreditation::create([
            'code' => 'C',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        //
    }
}
