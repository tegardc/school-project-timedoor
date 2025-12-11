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
        Schema::table('school_validations', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'alumni'])->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_validations', function (Blueprint $table) {
            $table->dropColumn('status');
            //
        });
    }
};
