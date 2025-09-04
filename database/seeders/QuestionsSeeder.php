<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            'Kelengkapan Fasilitas',
            'Proses Pembelajaran',
            'Pelayanan Sekolah',
            'Pelayanan Keamanan',
            'Ekstrakulikuler',
        ];

        foreach ($questions as $question) {
            Question::create(['question' => $question]);
        }
        //
    }
}
