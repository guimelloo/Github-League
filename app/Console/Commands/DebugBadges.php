<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use Illuminate\Console\Command;

class DebugBadges extends Command
{
    protected $signature = 'debug:badges';
    protected $description = 'Debug badges display issue';

    public function handle()
    {
        $profile = GithubProfile::where('github_username', 'guimelloo')->first();
        
        if (!$profile) {
            $this->error('Profile not found');
            return;
        }

        $this->info('Profile Data:');
        $this->line('Username: ' . $profile->github_username);
        $this->line('Top Language: ' . $profile->top_language);
        $this->line('Language Scores Type: ' . gettype($profile->language_scores));
        $this->line('Language Scores Count: ' . count($profile->language_scores ?? []));
        
        $this->info('\nLanguage Scores:');
        foreach ($profile->language_scores ?? [] as $language => $score) {
            $this->line("  {$language}: {$score}");
        }

        $this->info('\nChecking 100k+ filter:');
        $filtered = collect($profile->language_scores ?? [])
            ->filter(fn($score) => $score >= 100000)
            ->toArray();
        
        $this->line('Filtered languages (100k+): ' . count($filtered));
        foreach ($filtered as $language => $score) {
            $this->line("  ✓ {$language}: {$score}");
        }
    }
}
