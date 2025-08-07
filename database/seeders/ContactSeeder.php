<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'type' => 'phone',
                'value' => '08123456789',
                'schoolDetailId' => 1
            ],
            [
                'type' => 'email',
                'value' => 'oBzjy@example.com',
                'schoolDetailId' => 1
            ]
            ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
