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
        Schema::create('user_child_school', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('childId')->nullable()->constrained('childs')->onDelete('cascade')->nullable();
            $table->foreignId('schoolDetailId')->constrained('school_details')->onDelete('cascade')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
            // $table->unique([
            //     'userId',
            //     'childId',
            //     'schoolDetailId'
            // ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_child_school');
    }
};
