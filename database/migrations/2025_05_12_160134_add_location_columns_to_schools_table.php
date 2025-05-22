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
        Schema::table('schools', function (Blueprint $table) {
            $table->unsignedBigInteger('provinceId')->after('id');
            $table->unsignedBigInteger('districtId')->after('id');
            $table->unsignedBigInteger('subDistrictId')->after('id');

            $table->foreign('provinceId')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('districtId')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('subDistrictId')->references('id')->on('sub_districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['provinceId']);
            $table->dropForeign(['districtId']);
            $table->dropForeign(['subDistrictId']);

            $table->dropColumn(['provinceId', 'districtId', 'subDistrictId']);
        });
    }
};
