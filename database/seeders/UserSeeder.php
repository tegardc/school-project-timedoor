<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN ===
        $admin = User::create([
            'fullname'   => 'Admin Tester',
            'email'      => 'admin@example.com',
            'gender'     => 'female',
            'phoneNo'    => '081111111111',
            'password'   => Hash::make('Admin#123'), // sesuai rule password
            'createdAt'  => now(),
            'updatedAt'  => now(),
        ]);
        $admin->assignRole('admin');

        // === PARENT ===
        $parent = User::create([
            'fullname'   => 'Parent Tester',
            'email'      => 'parent01@example.com',
            'gender'     => 'male',
            'phoneNo'    => '08123456789',
            'password'   => Hash::make('Parent#123'),
            'createdAt'  => now(),
            'updatedAt'  => now(),
        ]);
        $parent->assignRole('parent');

        // Buat child untuk parent
        $child = Child::create([
            'userId'           => $parent->id,
            'name'             => 'Child Tester',
            'nisn'             => '654321',
            'relation'         => 'Orang Tua',
            'schoolDetailId'   => 1, // pastikan ada SchoolDetail dengan id=1
            'schoolValidation' => null,
            'createdAt'        => now(),
            'updatedAt'        => now(),
        ]);

        // Hubungkan parent + child + sekolah lewat pivot
        $parent->childSchoolDetails()->attach(1, [
            'childId'    => $child->id,
            'createdAt'  => now(),
            'updatedAt'  => now(),
        ]);

        // === STUDENT ===
        $student = User::create([
            'fullname'          => 'Student Tester',
            'email'             => 'student01@example.com',
            'gender'            => 'female',
            'phoneNo'           => '081444444444',
            'dateOfBirth'       => '2007-05-10',
            'nisn'              => '777777',
            'schoolValidation' => null,
            'password'          => Hash::make('Student#123'),
            'createdAt'         => now(),
            'updatedAt'         => now(),
        ]);
        $student->assignRole('student');

        // hubungkan student langsung ke sekolah (pivot tanpa childId)
        $student->childSchoolDetails()->attach(1, [
            'childId'    => null,
            'createdAt'  => now(),
            'updatedAt'  => now(),
        ]);
    }
}
