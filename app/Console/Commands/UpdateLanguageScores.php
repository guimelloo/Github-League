<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use App\Http\Controllers\GitHub\Service\GithubService;
use Illuminate\Console\Command;

class UpdateLanguageScores extends Command
{
    protected $signature = 'profiles:update-language-scores';
    protected $description = 'Update language_scores for all existing profiles based on their GitHub data';

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

        $this->info("🔄 Updating language scores for {$profiles->count()} profiles...");
        $this->line('');

        $updated = 0;
        $failed = 0;

        foreach ($profiles as $profile) {
            try {
                $scoreData = $service->getScore($profile->github_username, $profile->github_token);
                $languageScores = $scoreData['languages'] ?? [];
                $topLanguage = $scoreData['top_language'] ?? null;
                $languageCount = count($languageScores);

                $profile->update([
                    'language_scores' => $languageScores,
                    'top_language' => $topLanguage,
                ]);
                $updated++;

                $this->line("✓ {$profile->github_username} - {$languageCount} languages found" . ($topLanguage ? " (Top: $topLanguage)" : ""));
            } catch (\Exception $e) {
                $failed++;
                $this->line("✗ {$profile->github_username} - Error: {$e->getMessage()}");
            }
        }

        $this->line('');
        $this->info("✅ Updated: $updated profiles");
        if ($failed > 0) {
            $this->error("❌ Failed: $failed profiles");
        }
    }
}
