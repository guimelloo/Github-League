<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use App\Models\ScoreHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RecordDailyScoreHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'score:record-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record daily score history for all users with their ranking position';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();

        // Pegar todos os perfis ordenados por score (descendente)
        $profiles = GithubProfile::orderByDesc('score')->get();

        if ($profiles->isEmpty()) {
            $this->info('No GitHub profiles found.');
            return;
        }

        $recorded = 0;

        // For each profile, assign position and record
        foreach ($profiles as $position => $profile) {
            // Check if a record already exists for today
            $exists = ScoreHistory::where('github_profile_id', $profile->id)
                ->whereDate('recorded_at', $today)
                ->exists();

            if (!$exists) {
                ScoreHistory::create([
                    'github_profile_id' => $profile->id,
                    'score' => $profile->score,
                    'position' => $position + 1, // Position starts at 1
                    'recorded_at' => $today,
                ]);
                $recorded++;
            }
        }

        $this->info("Score history recorded for $recorded profiles on $today");
    }
}
