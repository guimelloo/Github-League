<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::create([
            'name' => 'Série C',
            'min_score' => 0,
            'max_score' => 99999,
            'color' => '#888888',
            'icon' => '🥉',
            'order' => 1,
        ]);

        Division::create([
            'name' => 'Série B',
            'min_score' => 100000,
            'max_score' => 900000,
            'color' => '#C0C0C0',
            'icon' => '🥈',
            'order' => 2,
        ]);

        Division::create([
            'name' => 'Série A',
            'min_score' => 1000000,
            'max_score' => null,
            'color' => '#FFD700',
            'icon' => '🥇',
            'order' => 3,
        ]);
    }
}
