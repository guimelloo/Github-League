<?php

namespace App\Http\Controllers\User\Service;

use App\Models\User;

class UserService
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function getHighestScoringUsers()
    {
        return $this->user->with('githubProfile.scoreHistory')
            ->get()
            ->sortByDesc(function ($user) {
                return $user->githubProfile->score ?? 0;
            })
            ->take(3)
            ->map(function ($user) {
                $user->streak = $this->calculateStreak($user->githubProfile);
                return $user;
            })
            ->values();
    }

    public function getAllUsers()
    {
        return $this->user->with('githubProfile.scoreHistory')
            ->get()
            ->sortByDesc(function ($user) {
                return $user->githubProfile->score ?? 0;
            })
            ->map(function ($user) {
                $user->streak = $this->calculateStreak($user->githubProfile);
                return $user;
            })
            ->values();
    }

    private function calculateStreak($githubProfile)
    {
        if (!$githubProfile || $githubProfile->scoreHistory->isEmpty()) {
            return 0;
        }

        $history = $githubProfile->scoreHistory->sortByDesc('recorded_at');

        $streak = 0;
        $currentPosition = null;

        foreach ($history as $record) {
            if ($currentPosition === null) {
                $currentPosition = $record->position;
                $streak = 1;
            } elseif ($record->position === $currentPosition) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
