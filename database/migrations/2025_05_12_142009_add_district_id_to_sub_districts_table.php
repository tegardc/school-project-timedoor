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
        Schema::table('sub_districts', function (Blueprint $table) {
            $table->unsignedBigInteger("districtId")->after("id");
            $table->foreign("districtId")->references('id')->on('districts')->onDelete('cascade');

            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_districts', function (Blueprint $table) {
            $table->dropForeign(['districtId']);
            $table->dropColumn('districtId');
            //
        });
    }
};
