<?php

namespace Database\Seeders;

use App\Models\SchoolStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSchool extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolStatus::create(['name' => 'Negeri']);
        SchoolStatus::create(['name' => 'Swasta']);
    }
}
