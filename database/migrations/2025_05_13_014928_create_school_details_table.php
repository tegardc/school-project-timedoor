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
            $table->unsignedBigInteger('schoolId')->nullable();
            $table->unsignedBigInteger('statusId')->nullable();
            $table->unsignedBigInteger('educationLevelId')->nullable();
            $table->unsignedBigInteger('addressId')->nullable();
            $table->string('ownershipStatus')->nullable();
            $table->date('dateEstablishmentDecree')->nullable();
            $table->string('operationalLicense')->nullable();
            $table->date('dateOperationalLicense')->nullable();
            $table->string('principal')->nullable();
            $table->string('operator')->nullable();
            $table->unsignedBigInteger('accreditationId');
            $table->unsignedBigInteger('educationProgramId')->nullable();
            $table->string('curriculum')->nullable();
            // $table->string('telpNo')->nullable();
            $table->decimal('tuitionFee', 10, 2)->nullable();
            $table->integer('numStudent')->nullable();
            $table->integer('numTeacher')->nullable();
            $table->text('movie')->nullable();
            $table->text('examInfo')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();

            $table->foreign('schoolId')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('statusId')->references('id')->on('school_statuses')->onDelete('cascade');
            $table->foreign('educationLevelId')->references('id')->on('education_levels')->onDelete('cascade');
            $table->foreign('accreditationId')->references('id')->on('accreditations')->onDelete('cascade');
            $table->foreign('educationProgramId')->references('id')->on('education_programs')->onDelete('cascade');
            $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade');
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
