<?php

namespace App\Http\Controllers\GitHub\Service;

use App\Models\GithubProfile;
use Github\Client;
use Illuminate\Http\Request;

class GithubService
{
    private $client;
    private $githubClient;

    public function __construct()
    {
        $this->client = new Client();
        $this->githubClient = new GithubProfile();
    }

    public function saveGithubData($userId, $githubData)
    {
        $this->client->authenticate(
            $githubData['token'],
            null,
           Client::AUTH_ACCESS_TOKEN
        );

        $scoreData = $this->getScore($githubData['username']);
        $totalScore = $scoreData['total_score'] ?? 0;
        $languageScores = $scoreData['languages'] ?? [];
        $topLanguage = $scoreData['top_language'] ?? null;
        $divisionId = $this->getDivision($totalScore);

        $profile = $this->githubClient->updateOrCreate(
            ['user_id' => $userId],
            [
                'github_username' => $githubData['username'] ?? null,
                'github_token' => $githubData['token'] ?? null,
                'avatar_url' => $githubData['avatar'] ?? null,
                'verified_at' => now(),
                'score' => $totalScore,
                'language_scores' => $languageScores,
                'top_language' => $topLanguage,
                'division_id' => $divisionId,
            ]
        );

        return $profile;
    }

    public function getScore($username, $token = null)
    {
        try {
            // Autentica se o token foi passado
            if ($token) {
                $this->client->authenticate(
                    $token,
                    null,
                    Client::AUTH_ACCESS_TOKEN
                );
            }

            $repos = $this->client->api('user')->repositories($username);

            if (empty($repos)) {
                return [
                    'total_score' => 0,
                    'languages' => [],
                    'top_language' => null
                ];
            }

            $totalStars = 0;
            $totalForks = 0;
            $totalLines = 0;
            $ownRepos = 0;

            $languageStats = [];

            foreach ($repos as $repo) {
                if ($repo['fork'] === true) {
                    continue;
                }

                $ownRepos++;
                $stars = $repo['stargazers_count'] ?? 0;
                $forks = $repo['forks_count'] ?? 0;

                $totalStars += $stars;
                $totalForks += $forks;

                try {
                    $languages = $this->client->api('repo')->languages(
                        $username,
                        $repo['name']
                    );

                    $repoTotalLines = array_sum($languages);
                    $totalLines += $repoTotalLines;

                    foreach ($languages as $language => $bytes) {

                        if (!isset($languageStats[$language])) {
                            $languageStats[$language] = [
                                'lines' => 0,
                                'repos' => 0,
                            ];
                        }

                        $languageStats[$language]['lines'] += $bytes;
                        $languageStats[$language]['repos']++;
                    }

                } catch (\Exception $e) {
                    continue;
                }
            }


            if ($ownRepos === 0) {
                return [
                    'total_score' => 0,
                    'languages' => [],
                    'top_language' => null
                ];
            }

            // Calculate total score with proper scaling
            $totalScore = ($totalLines / $ownRepos) * 50
                        + ($totalStars * 100)
                        + ($totalForks * 50);

            $languageScores = [];
            $topLanguage = null;
            $maxLines = 0;

            foreach ($languageStats as $language => $data) {
                $languagePercentage = $totalLines > 0 ? ($data['lines'] / $totalLines) : 0;

                // Calculate language score with stronger scaling
                // Weight: language's share of total score based on code contribution
                $score = ($data['lines'] / $ownRepos) * 50
                    + ($languagePercentage * $totalStars * 100)
                    + ($languagePercentage * $totalForks * 50);

                $languageScores[$language] = round($score);

                if ($data['lines'] > $maxLines) {
                    $maxLines = $data['lines'];
                    $topLanguage = $language;
                }
            }

            return [
                'total_score' => round($totalScore),
                'languages' => $languageScores,
                'top_language' => $topLanguage
            ];

        } catch (\Exception $e) {
            return [
                'total_score' => 0,
                'languages' => [],
                'top_language' => null
            ];
        }
    }

    public function getDivision($score)
    {
        if ($score >= 1000000) {
            return 3;
        }

        if ($score >= 100000) {
            return 2;
        }

        return 1;
    }
}

