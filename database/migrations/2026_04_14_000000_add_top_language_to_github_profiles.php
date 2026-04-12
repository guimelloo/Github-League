<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('github_profiles', function (Blueprint $table) {
            $table->string('top_language')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('github_profiles', function (Blueprint $table) {
            $table->dropColumn('top_language');
        });
    }
};
