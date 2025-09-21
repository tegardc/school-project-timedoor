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
        Schema::create('childs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('nisn')->nullable();
            $table->unsignedBigInteger('schoolDetailId')->nullable();
            $table->string('fullname');
            $table->date('dateOfBirth')->nullable();
            $table->string('schoolValidation')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNo')->nullable();
            $table->enum('relation', ['Orang Tua', 'Wali'])->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('schoolDetailId')->references('id')->on('school_details')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childs');
    }
};
