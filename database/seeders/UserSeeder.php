<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\SchoolDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "firstName" => "admin",
            "lastName" => "tester",
            "username" => "admin",
            "email" => "admin@gmail.com",
            "gender" => "female",
            "phoneNo" => "086712721812",
            "password" => Hash::make("Admin#123"),
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $admin->assignRole('admin');

        $parent = User::create([
    'firstName' => 'Parent',
    'lastName' => 'Tester',
    'username' => 'parent01',
    'email' => 'parent01@example.com',
    'gender' => 'male',
    'phoneNo' => '08123456789',
    'password' => Hash::make('password123'),
]);

$parent->assignRole('parent');

$child = Child::create([
    'userId' => $parent->id,
    'name' => 'Child Tester',
    'nis' => '654321',
]);

$parent->childSchoolDetails()->attach(1, [
    'childId' => $child->id,
]);


    $parent2 = User::create([
    'firstName' => 'Parent',
    'lastName' => 'Tester',
    'username' => 'parent02',
    'email' => 'parent02@example.com',
    'gender' => 'male',
    'phoneNo' => '08123456789',
    'password' => Hash::make('password123'),
]);

$parent2->assignRole('parent');

$child2 = Child::create([
    'userId' => $parent2->id,
    'name' => 'Child Tester',
    'nis' => '6543221',
]);

$parent2->childSchoolDetails()->attach(2, [
    'childId' => $child2->id,
]);
    }
}
