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
        Schema::create('school_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institutionCode')->unique();
            $table->unsignedBigInteger('schoolId');
            $table->unsignedBigInteger('statusId');
            $table->unsignedBigInteger('educationLevelId');
            $table->string('ownershipStatus');
            $table->date('dateEstablishmentDecree');
            $table->string('operationalLicense');
            $table->date('dateOperationalLicense');
            $table->string('principal');
            $table->string('operator');
            $table->unsignedBigInteger('accreditationId');
            $table->string('curriculum');
            $table->string('telpNo');
            $table->decimal('tuitionFee', 10, 2);
            $table->integer('numStudent');
            $table->integer('numTeacher');
            $table->text('movie')->nullable();
            $table->text('examInfo')->nullable();
            $table->timestamps();

            $table->foreign('schoolId')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('statusId')->references('id')->on('school_statuses')->onDelete('cascade');
            $table->foreign('educationLevelId')->references('id')->on('education_levels')->onDelete('cascade');
            $table->foreign('accreditationId')->references('id')->on('accreditations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_details');
    }
};
