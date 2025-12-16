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
        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reviewId');
            $table->unsignedBigInteger('userId');

            $table->unique(['reviewId', 'userId']);

            $table->foreign('reviewId')->references('id')->on('reviews')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_likes');
    }
};
