<?php

namespace App\Http\Controllers\Divisions\Service;

use App\Models\GithubProfile;

class LanguageDivisionService
{
    private $githubProfile;

    public function __construct()
    {
        $this->githubProfile = new GithubProfile();
    }

    public function getLanguageDivisions()
    {
        // Coletar todas as linguagens
        $allLanguages = [];
        $profiles = $this->githubProfile::with('user')
            ->whereNotNull('language_scores')
            ->get();

        foreach ($profiles as $profile) {
            if ($profile->language_scores) {
                foreach ($profile->language_scores as $language => $score) {
                    if (!isset($allLanguages[$language])) {
                        $allLanguages[$language] = [];
                    }
                    $allLanguages[$language][] = [
                        'id' => $profile->id,
                        'user_name' => $profile->user->name,
                        'github_username' => $profile->github_username,
                        'avatar_url' => $profile->avatar_url,
                        'score' => $score,
                        'total_score' => $profile->score,
                    ];
                }
            }
        }

        $languageDivisions = [];
        foreach ($allLanguages as $language => $profiles) {
            usort($profiles, function ($a, $b) {
                return $b['score'] - $a['score'];
            });
            
            $languageDivisions[$language] = array_slice($profiles, 0, 10);
        }

        uasort($languageDivisions, function ($a, $b) {
            return count($b) - count($a);
        });

        return $languageDivisions;
    }
}

