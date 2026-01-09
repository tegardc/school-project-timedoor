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
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['userId']);

            $table->unsignedBigInteger('userId')->nullable()->change();

            if (!Schema::hasColumn('reviews', 'reviewer_name')) {
                $table->string('reviewer_name')->nullable()->after('userId');
            }
            if (!Schema::hasColumn('reviews', 'source')) {
                $table->string('source')->default('internal')->after('reviewer_name');
            }
            $table->foreign('userId')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['reviewer_name', 'source']);
        });
    }
};
