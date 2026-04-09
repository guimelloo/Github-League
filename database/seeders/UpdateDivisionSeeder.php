<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class UpdateDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Atualizar Division C
        Division::where('order', 1)->update([
            'name' => 'Divisão C',
            'icon' => 'bronze',
        ]);

        // Atualizar Division B
        Division::where('order', 2)->update([
            'name' => 'Divisão B',
            'icon' => 'silver',
        ]);

        // Atualizar Division A
        Division::where('order', 3)->update([
            'name' => 'Divisão A',
            'icon' => 'gold',
        ]);
    }
}
