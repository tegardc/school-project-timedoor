<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceDistrictSeeder::class,
            RoleSeeder::class,
            EducationLevel::class,
            Accreditation::class,
            StatusSchool::class,
            UserSeeder::class,
            SchoolSeeder::class,
            SchoolDetailSeeder::class,
            SchoolGallerySeeder::class

        ]);
        $user = User::find(1);
        $user->assignRole('parent');
    }
}
