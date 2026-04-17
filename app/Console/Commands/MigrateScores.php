<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use App\Http\Controllers\GitHub\Service\GithubService;
use Illuminate\Console\Command;

class MigrateScores extends Command
{
    protected $signature = 'scores:migrate';
    protected $description = 'Migrate all existing users to new scoring system (excluding forks, proportional language distribution)';

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

        $this->info("🔄 Migrating {$profiles->count()} profiles to new scoring system...");
        $this->line('');

        $updated = 0;
        $failed = 0;
        $scoreIncreased = 0;
        $scoreDecreased = 0;
        $totalScoreDiff = 0;

        foreach ($profiles as $profile) {
            try {
                $scoreData = $service->getScore($profile->github_username, $profile->github_token);
                $totalScore = $scoreData['total_score'] ?? 0;
                $languageScores = $scoreData['languages'] ?? [];
                $topLanguage = $scoreData['top_language'] ?? null;
                $newDivisionId = $service->getDivision($totalScore);

                $oldScore = $profile->score;
                $oldDivisionId = $profile->division_id;
                $scoreDiff = $totalScore - $oldScore;

                $profile->update([
                    'score' => $totalScore,
                    'language_scores' => $languageScores,
                    'top_language' => $topLanguage,
                    'division_id' => $newDivisionId,
                ]);

                $updated++;
                $totalScoreDiff += $scoreDiff;

                if ($totalScore > $oldScore) {
                    $scoreIncreased++;
                    $diff = $totalScore - $oldScore;
                    $this->line("📈 {$profile->github_username} +{$diff} ({$oldScore} → {$totalScore})");
                } elseif ($totalScore < $oldScore) {
                    $scoreDecreased++;
                    $diff = $oldScore - $totalScore;
                    $this->line("📉 {$profile->github_username} -{$diff} ({$oldScore} → {$totalScore})");
                } else {
                    $this->line("➡️  {$profile->github_username} (unchanged: {$totalScore})");
                }

                if ($oldDivisionId != $newDivisionId) {
                    $this->line("   🏆 Division: {$oldDivisionId} → {$newDivisionId}");
                }

            } catch (\Exception $e) {
                $failed++;
                $this->line("✗ {$profile->github_username} - Error: {$e->getMessage()}");
            }
        }

        $this->line('');
        $this->info("✅ Migration Complete!");
        $this->line("  Migrated: {$updated} profiles");
        $this->line("    ├─ Increased: {$scoreIncreased}");
        $this->line("    ├─ Decreased: {$scoreDecreased}");
        $this->line("    └─ Unchanged: " . ($updated - $scoreIncreased - $scoreDecreased));
        $this->line("  Total score adjustment: " . ($totalScoreDiff >= 0 ? "+" : "") . $totalScoreDiff);
        if ($failed > 0) {
            $this->line("  ❌ Failed: {$failed} profiles");
        }
    }
}
