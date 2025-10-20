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
        Schema::create('analyzed_strings', function (Blueprint $table) {
           $table->id();
            $table->string('sha256_hash')->unique();
            $table->text('value');
            $table->json('properties');
            $table->timestamps();
            $table->index('sha256_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyzed_strings');
    }
};
