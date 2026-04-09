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
        $divisionId = $this->getDivision($scoreData);
        
        $profile = $this->githubClient->updateOrCreate(
            ['user_id' => $userId],
            [
                'github_username' => $githubData['username'] ?? null,
                'github_token' => $githubData['token'] ?? null,
                'avatar_url' => $githubData['avatar'] ?? null,
                'verified_at' => now(),
                'score' => $scoreData ?? 0,
                'division_id' => $divisionId,
            ]
        );

        return $profile;
    }

    public function getScore($username)
    {
        try {
            $user = $this->client->api('user')->show($username);
            $repos = $this->client->api('user')->repositories($username);

            if (empty($repos) || count($repos) === 0) {
                return 0;
            }

            $stars = 0;
            $forks = 0;
            $lines = 0;

            foreach ($repos as $repo) {
                $stars += $repo['stargazers_count'] ?? 0;
                $forks += $repo['forks_count'] ?? 0;

                try {
                    $languages = $this->client->api('repo')->languages(
                        $username,
                        $repo['name']
                    );
                    $lines += array_sum($languages);
                } catch (\Exception $e) {
                    continue;
                }
            }

            $score = ($lines / count($repos)) * 2 + ($stars * 2) + $forks;

            return round($score);
        } catch (\Exception $e) {
            // If any error occurs, return 0
            return 0;
        }
    }

    public function getDivision($score)
    {
        // Série A: 1.000.000+
        if ($score >= 1000000) {
            return 3; // ID da Série A
        }
        
        // Série B: 100.000 - 900.000
        if ($score >= 100000) {
            return 2; // ID da Série B
        }
        
        // Série C: 0 - 99.999
        return 1; // ID da Série C
    }
}
