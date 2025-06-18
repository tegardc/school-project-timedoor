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
        Schema::create('question_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('question')->nullable();
            $table->enum('questionTypes', ['textArea', 'radio','text','file']);
            $table->integer('scoreMin')->nullable();
            $table->integer('scoreMax')->nullable();
            $table->integer('scoreLabelMin')->nullable();
            $table->integer('scoreLabelMax')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_reviews');
    }
};
