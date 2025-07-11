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
            'Seberapa baik fasilitas yang disediakan oleh sekolah?',
            'Seberapa kompeten guru-guru dalam menyampaikan materi?',
            'Bagaimana pendapat Anda tentang kebersihan dan kenyamanan lingkungan sekolah?',
            'Apakah komunikasi antara pihak sekolah dan orang tua berjalan dengan baik?',
            'Seberapa puas Anda terhadap kegiatan ekstrakurikuler yang disediakan sekolah?',
        ];

        foreach ($questions as $question) {
            Question::create(['question' => $question]);
        }
        //
    }
}
