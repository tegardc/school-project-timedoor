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
        Schema::table('districts', function (Blueprint $table) {
            $table->unsignedBigInteger("provinceId")->after("id");
            $table->foreign("provinceId")->references('id')->on('provinces')->onDelete('cascade');

            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropForeign(['provinceId']);
            $table->dropColumn('provinceId');
            //
        });
    }
};
