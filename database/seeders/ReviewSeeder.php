<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Review;
use App\Models\ReviewDetail;
use App\Models\SchoolDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $questions = Question::all();
        $reviewers = User::role(['student', 'parent'])->get();

        if ($questions->isEmpty() || $reviewers->isEmpty()) {
            $this->command->warn("Seeder skipped: no questions/reviewers found");
            return;
        }

        foreach ($reviewers as $reviewer) {
            // 🔎 ambil sekolah sesuai role
            if ($reviewer->hasRole('student')) {
                $schoolDetails = $reviewer->childSchoolDetails()->get();
            } elseif ($reviewer->hasRole('parent')) {
                $schoolDetails = $reviewer->childSchoolDetails()->get();
            } else {
                $schoolDetails = collect(); // fallback
            }

            if ($schoolDetails->isEmpty()) {
                continue;
            }

            foreach ($schoolDetails as $schoolDetail) {
                $review = Review::create([
                    'userId' => $reviewer->id,
                    'schoolDetailId' => $schoolDetail->id,
                    'reviewText' => "Review dari {$reviewer->firstName} untuk {$schoolDetail->name}",
                    'status' => Review::STATUS_APPROVED,
                    'rating' => 0
                ]);

                $totalScore = 0;
                foreach ($questions as $question) {
                    $score = rand(1, 5);
                    $totalScore += $score;

                    ReviewDetail::create([
                        'reviewId' => $review->id,
                        'questionId' => $question->id,
                        'score' => $score,
                    ]);
                }

                $review->update([
                    'rating' => round($totalScore / $questions->count(), 2)
                ]);
            }
        }
    }
}
