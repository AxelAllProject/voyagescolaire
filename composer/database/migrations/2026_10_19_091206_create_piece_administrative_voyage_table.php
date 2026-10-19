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
        Schema::create('piece_administrative_voyage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piece_administrative_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voyage_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['piece_administrative_id', 'voyage_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piece_administrative_voyage');
    }
};
