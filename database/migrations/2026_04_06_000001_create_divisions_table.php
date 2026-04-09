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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // "Série A", "Série B", "Série C"
            $table->integer('min_score'); // Score mínimo
            $table->integer('max_score')->nullable(); // null = sem limite superior
            $table->string('color'); // Cor em hex (#FFD700 para ouro)
            $table->string('icon'); // bronze, silver, gold, etc
            $table->integer('order'); // Ordem de exibição (1, 2, 3...)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
