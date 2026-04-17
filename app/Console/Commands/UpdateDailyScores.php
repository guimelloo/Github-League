<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use App\Http\Controllers\GitHub\Service\GithubService;
use Illuminate\Console\Command;

class UpdateDailyScores extends Command
{
    protected $signature = 'score:update-daily';
    protected $description = 'Update scores for all GitHub profiles daily and recalculate divisions';

    public function handle()
    {
        $service = new GithubService();
        $profiles = GithubProfile::whereNotNull('github_username')
            ->whereNotNull('github_token')
            ->get();

        if ($profiles->isEmpty()) {
            $this->error('❌ No GitHub profiles found.');
            return;
        }

        $this->info("🔄 Updating scores for {$profiles->count()} profiles...");
        $this->line('');

        $updated = 0;
        $unchanged = 0;
        $failed = 0;
        $scoreIncreased = 0;
        $scoreDecreased = 0;

        foreach ($profiles as $profile) {
            try {
                $scoreData = $service->getScore($profile->github_username, $profile->github_token);
                $totalScore = $scoreData['total_score'] ?? 0;
                $languageScores = $scoreData['languages'] ?? [];
                $topLanguage = $scoreData['top_language'] ?? null;
                $newDivisionId = $service->getDivision($totalScore);

                $oldScore = $profile->score;
                $oldDivisionId = $profile->division_id;

                if ($oldScore != $totalScore || $profile->top_language != $topLanguage) {
                    $profile->update([
                        'score' => $totalScore,
                        'language_scores' => $languageScores,
                        'top_language' => $topLanguage,
                        'division_id' => $newDivisionId,
                    ]);

                    $updated++;

                    if ($totalScore > $oldScore) {
                        $scoreIncreased++;
                        $diff = $totalScore - $oldScore;
                        $this->line("📈 {$profile->github_username} +{$diff} ({$oldScore} → {$totalScore})");
                    } elseif ($totalScore < $oldScore) {
                        $scoreDecreased++;
                        $diff = $oldScore - $totalScore;
                        $this->line("📉 {$profile->github_username} -{$diff} ({$oldScore} → {$totalScore})");
                    }

                    if ($oldDivisionId != $newDivisionId) {
                        $this->line("   🏆 Division: {$oldDivisionId} → {$newDivisionId}");
                    }
                } else {
                    $unchanged++;
                }

            } catch (\Exception $e) {
                $failed++;
                $this->line("✗ {$profile->github_username} - Error: {$e->getMessage()}");
            }
        }

        $this->line('');
        $this->info("✅ Update Complete!");
        $this->line("  Updated: {$updated} profiles");
        $this->line("    ├─ Increased: {$scoreIncreased}");
        $this->line("    └─ Decreased: {$scoreDecreased}");
        $this->line("  Unchanged: {$unchanged} profiles");
        if ($failed > 0) {
            $this->line("  ❌ Failed: {$failed} profiles");
        }
    }
}
