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
            SchoolSeeder::class,
            SchoolDetailSeeder::class,
            SchoolGallerySeeder::class,
            UserSeeder::class,
            QuestionsSeeder::class

        ]);
        $user = User::find(1);
        $user->assignRole('parent');
    }
}
