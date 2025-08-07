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
        Schema::create('school_detail_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schoolDetailId')->constrained('school_details')->onDelete('cascade');
            $table->foreignId('facilityId')->constrained('facilities')->onDelete('cascade');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_detail_facility');
    }
};
