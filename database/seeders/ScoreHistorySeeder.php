<?php

namespace Database\Seeders;

use App\Models\GithubProfile;
use App\Models\ScoreHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ScoreHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = GithubProfile::all();

        if ($profiles->isEmpty()) {
            $this->command->info('Nenhum perfil GitHub encontrado. Execute o DatabaseSeeder primeiro.');
            return;
        }

        // Para cada perfil, criar histórico de scores dos últimos 7 dias
        foreach ($profiles as $index => $profile) {
            // Simular streak variável: primeiro user ter streak de 7 dias, segundo de 3, terceiro de 1
            $streakDays = [7, 3, 1][$index % 3] ?? 1;
            
            // Posição variável para cada um
            $position = ($index % 3) + 1; // Posição 1, 2 ou 3

            for ($i = $streakDays - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->toDateString();

                // Verificar se já existe para evitar duplicatas
                $exists = ScoreHistory::where('github_profile_id', $profile->id)
                    ->whereDate('recorded_at', $date)
                    ->exists();

                if (!$exists) {
                    ScoreHistory::create([
                        'github_profile_id' => $profile->id,
                        'score' => $profile->score + ($i * 10), // Score aumenta nos últimos dias
                        'position' => $position,
                        'recorded_at' => $date,
                    ]);
                }
            }
        }

        $this->command->info('Score history populado com sucesso!');
    }
}
