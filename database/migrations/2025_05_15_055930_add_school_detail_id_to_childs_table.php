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
        Schema::table('childs', function (Blueprint $table) {
            $table->unsignedBigInteger('schoolDetailId')->after('id');
            $table->foreign('schoolDetailId')->references('id')->on('school_details')->onDelete('cascade');

            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['schoolDetailId']);
            $table->dropColumn('schoolDetailId');
            //
        });
    }
};
