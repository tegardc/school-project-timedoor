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
        ModelsAccreditation::create(['code' => 'Unggulan']);
        ModelsAccreditation::create(['code' => 'A']);
        ModelsAccreditation::create(['code' => 'B']);
        ModelsAccreditation::create(['code' => 'C']);
        //
    }
}
