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
        Schema::create('school_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schoolId');
            $table->unsignedBigInteger('schoolDetailId');
            $table->string('imageUrl');
            $table->boolean('isCover')->default(false);
            $table->timestamps();


            $table->foreign('schoolId')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('schoolDetailId')->references('id')->on('school_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_images');
    }
};
