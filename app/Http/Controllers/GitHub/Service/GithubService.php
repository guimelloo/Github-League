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
        $profile = $this->githubClient->updateOrCreate(
            ['user_id' => $userId],
            [
                'github_username' => $githubData['username'] ?? null,
                'github_token' => $githubData['token'] ?? null,
                'avatar_url' => $githubData['avatar'] ?? null,
                'verified_at' => now(),
                'score' => $scoreData ?? 0,
            ]
        );

        return $profile;
    }

    public function getScore($username)
    {
        try {
            $user = $this->client->api('user')->show($username);
            $repos = $this->client->api('user')->repositories($username);

            // If user has no repos, return 0
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
                    // Skip if unable to get languages
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
}
