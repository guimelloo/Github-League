<?php

namespace App\Http\Controllers\User\Service;

use App\Models\User;
use App\Models\GithubProfile;

class UserService
{
    private $user;
    private $githubProfile;

    public function __construct()
    {
        $this->user = new User();
        $this->githubProfile = new GithubProfile();
    }

    public function getHighestScoringUsers()
    {
        return $this->user->with('githubProfile')
            ->get()
            ->sortByDesc(function ($user) {
                return $user->githubProfile->score ?? 0;
            })
            ->take(3)
            ->values();
    }

    public function getAllUsers()
    {
        return $this->user->with('githubProfile')
            ->get()
            ->sortByDesc(function ($user) {
                return $user->githubProfile->score ?? 0;
            })
            ->values();
    }

    public function getUserByGithubUsername($githubUsername)
    {
        $profile = $this->githubProfile::where('github_username', $githubUsername)
            ->with('user', 'division')
            ->firstOrFail();

        return [
            'id' => $profile->id,
            'user_id' => $profile->user_id,
            'user_name' => $profile->user->name,
            'github_username' => $profile->github_username,
            'avatar_url' => $profile->avatar_url,
            'score' => $profile->score,
            'top_language' => $profile->top_language,
            'division' => $profile->division,
        ];
    }

    public function searchUsers($query)
    {
        $profiles = $this->githubProfile::whereHas('user', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })
            ->orWhere('github_username', 'like', "%{$query}%")
            ->with('user')
            ->limit(10)
            ->get()
            ->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'user_name' => $profile->user->name,
                    'github_username' => $profile->github_username,
                    'avatar_url' => $profile->avatar_url,
                    'score' => $profile->score,
                ];
            });

        return $profiles;
    }
}
