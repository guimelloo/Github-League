<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use Illuminate\Console\Command;

class DiagnoseBadgesProduction extends Command
{
    protected $signature = 'diagnose:badges {username?}';
    protected $description = 'Diagnose badge display issues in production';

    public function handle()
    {
        $username = $this->argument('username') ?: 'guimelloo';

        $profile = GithubProfile::where('github_username', $username)->first();

        if (!$profile) {
            $this->error("Profile not found for username: {$username}");
            $this->line('Available profiles:');
            GithubProfile::whereNotNull('github_username')
                ->select('github_username', 'score')
                ->orderBy('score', 'desc')
                ->limit(10)
                ->each(function($p) {
                    $this->line("  - {$p->github_username}: {$p->score} points");
                });
            return;
        }

        $this->info("=== BADGE DIAGNOSIS FOR: {$username} ===\n");

        $this->line("📊 PROFILE DATA:");
        $this->line("  Score: " . number_format($profile->score));
        $this->line("  Top Language: " . ($profile->top_language ?? 'NULL'));
        $this->line("  Language Scores Type: " . gettype($profile->language_scores));
        $this->line("  Language Scores Count: " . count($profile->language_scores ?? []));

        $this->line("\n💾 RAW DATABASE VALUE:");
        $raw = \DB::table('github_profiles')
            ->where('github_username', $username)
            ->select('language_scores')
            ->first();
        if ($raw) {
            $this->line("  Type: " . gettype($raw->language_scores));
            $this->line("  Length: " . strlen($raw->language_scores));
            $this->line("  First 200 chars: " . substr($raw->language_scores, 0, 200));
        }

        $this->line("\n📈 LANGUAGE SCORES (all):");
        if ($profile->language_scores) {
            foreach ($profile->language_scores as $lang => $score) {
                $badge = $score >= 100000 ? '✓' : '✗';
                $this->line("  {$badge} {$lang}: " . number_format($score));
            }
        } else {
            $this->line("  ⚠️  language_scores is NULL or empty");
        }

        $this->line("\n🏆 BADGES (100k+):");
        $badges100k = [];
        if ($profile->language_scores) {
            foreach ($profile->language_scores as $lang => $score) {
                if ($score >= 100000) {
                    $badges100k[$lang] = $score;
                }
            }
        }

        if (count($badges100k) > 0) {
            foreach ($badges100k as $lang => $score) {
                $this->line("  ✓ {$lang}: " . number_format($score));
            }
        } else {
            $this->warn("  ⚠️  No languages with 100k+ found!");
        }

        $this->line("\n🔍 JSON VALIDITY:");
        $json = $profile->language_scores;
        $isArray = is_array($json);
        $this->line("  Is Array: " . ($isArray ? 'YES' : 'NO'));

        if (!$isArray && is_string($json)) {
            $decoded = json_decode($json, true);
            $this->line("  Can decode JSON: " . ($decoded ? 'YES' : 'NO'));
            if (!$decoded) {
                $this->line("  JSON Error: " . json_last_error_msg());
            }
        }

        $this->line("\n📡 API RESPONSE TEST:");
        $this->line("  Testing Inertia::render() simulation...");

        // Simulate what would be sent to frontend
        $data = [
            'githubProfile' => $profile->load('division'),
        ];

        $json_response = json_encode($data);
        $this->line("  Serializable: " . ($json_response ? 'YES' : 'NO'));

        $decoded = json_decode($json_response, true);
        if ($decoded && isset($decoded['githubProfile']['language_scores'])) {
            $ls = $decoded['githubProfile']['language_scores'];
            $this->line("  Decoded count: " . count($ls));
            $count100k = count(array_filter($ls, fn($s) => $s >= 100000));
            $this->line("  Languages with 100k+: " . $count100k);
        }
    }
}
