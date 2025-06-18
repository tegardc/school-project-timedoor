<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answer_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionReviewId');
            $table->unsignedBigInteger('reviewId');
            $table->string('answerText')->nullable();
            $table->string('answerFile')->nullable();
            $table->decimal('score');

            $table->foreign('questionReviewId')->references('id')->on('question_reviews')->onDelete('cascade');
            $table->foreign('reviewId')->references('id')->on('reviews')->onDelete('cascade');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_questions');
    }
};
