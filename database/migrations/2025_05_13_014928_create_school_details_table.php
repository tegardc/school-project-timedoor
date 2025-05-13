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
            $table->string('institution_code')->unique();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('status_id')->constrained('school_statuses');
            $table->foreignId('education_level_id')->constrained('education_levels');
            $table->string('ownership_status');
            $table->date('date_enstablishment_decree');
            $table->date('date_operational_license');
            $table->string('principal');
            $table->string('operator');
            $table->foreignId('accreditation_id')->constrained('accreditations');
            $table->string('curriculum');
            $table->string('telp_no');
            $table->decimal('tuition_fee', 10, 2);
            $table->integer('num_student');
            $table->integer('num_teacher');
            $table->text('movie')->nullable();
            $table->text('exam_info')->nullable();
            $table->timestamps();
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
