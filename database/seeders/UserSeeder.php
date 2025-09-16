<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\SchoolDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN ===
        $admin = User::create([
            'firstName' => 'Admin',
            'lastName'  => 'Tester',
            'email'     => 'admin@example.com',
            'gender'    => 'female',
            'phoneNo'   => '081111111111',
            'password'  => Hash::make('Admin#123'), // sesuai rule: huruf besar, kecil, angka, simbol
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $admin->assignRole('admin');

        // === PARENT 1 ===
       $parent = User::create([
    'firstName' => 'Parent',
    'lastName'  => 'Tester',
    'email'     => 'parent01@example.com',
    'gender'    => 'male',
    'phoneNo'   => '08123456789',
    'password'  => Hash::make('password123'),
]);
$parent->assignRole('parent');

// Buat child tanpa userId
$child = Child::create([
    'name' => 'Child Tester',
    'nis'  => '654321',
]);

// Hubungkan parent + child + sekolah lewat pivot
$parent->childSchoolDetails()->attach(1, [  // 1 = schoolDetailId
    'childId' => $child->id,
]);


        // === STUDENT EXAMPLE ===
        $student = User::create([
            'firstName' => 'Student',
            'lastName'  => 'Tester',
            'email'     => 'student01@example.com',
            'gender'    => 'female',
            'phoneNo'   => '081444444444',
            'password'  => Hash::make('Student#123'),
            'nis'       => '777777', // kalau mau simpan NIS langsung di users
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $student->assignRole('student');
    }
}
