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
}
