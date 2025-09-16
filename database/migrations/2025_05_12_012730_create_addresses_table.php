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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provinceId')->nullable();
            $table->unsignedBigInteger('districtId')->nullable();
            $table->unsignedBigInteger('subDistrictId')->nullable();
            $table->string('village')->nullable();
            $table->string('street')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->foreign('provinceId')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('districtId')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('subDistrictId')->references('id')->on('sub_districts')->onDelete('cascade');

            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
