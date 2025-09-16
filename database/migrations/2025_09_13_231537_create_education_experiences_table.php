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
        Schema::create('education_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('educationLevelId');
            $table->unsignedBigInteger('schoolDetailId');
            $table->unsignedBigInteger('educationProgramId');
            $table->string('degree');

            $table->date('startDate');
            $table->date('endDate');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('educationLevelId')->references('id')->on('education_levels')->onDelete('cascade');
            $table->foreign('schoolDetailId')->references('id')->on('school_details')->onDelete('cascade');
            $table->foreign('educationProgramId')->references('id')->on('education_programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_experiences');
    }
};
