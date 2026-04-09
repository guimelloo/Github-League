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
        Schema::create('score_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('github_profile_id')->constrained('github_profiles')->onDelete('cascade');
            $table->integer('score');
            $table->integer('position'); // 1º, 2º, 3º lugar naquele dia
            $table->date('recorded_at'); // Data do registro
            $table->timestamps();

            // Index para facilitar queries
            $table->index(['github_profile_id', 'recorded_at']);
            $table->unique(['github_profile_id', 'recorded_at']); // Um registro por dia por usuário
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_history');
    }
};
