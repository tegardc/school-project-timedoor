<?php

namespace Database\Seeders;

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
        ]);
        $admin->assignRole('admin');
        //
    }
}
