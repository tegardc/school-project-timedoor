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
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        //
    }
}
