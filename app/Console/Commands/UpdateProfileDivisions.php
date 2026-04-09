<?php

namespace App\Console\Commands;

use App\Models\GithubProfile;
use App\Http\Controllers\GitHub\Service\GithubService;
use Illuminate\Console\Command;

class UpdateProfileDivisions extends Command
{
    protected $signature = 'profiles:update-divisions';
    protected $description = 'Update division_id for all existing profiles based on their score';

    public function handle()
    {
        $divisionService = new GithubService();
        $profiles = GithubProfile::all();
        
        $this->info("Atualizando divisões para {$profiles->count()} perfis...");
        
        $updated = 0;
        foreach ($profiles as $profile) {
            $divisionId = $divisionService->getDivision($profile->score);
            
            if ($profile->division_id !== $divisionId) {
                $profile->update(['division_id' => $divisionId]);
                $updated++;
                $this->line("✓ {$profile->github_username} → Division ID: {$divisionId}");
            }
        }
        
        $this->info("Pronto! {$updated} perfis atualizados.");
    }
}
