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
        Schema::create('contacts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('schoolDetailId')->constrained('school_details')->onDelete('cascade');
        $table->enum('type', ['phone', 'email', 'website', 'whatsapp', 'facebook', 'instagram', 'other']);
        $table->string('value');
        $table->timestamp('createdAt')->nullable();
        $table->timestamp('updatedAt')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
