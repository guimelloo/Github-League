<?php

namespace App\Http\Controllers\GitHub;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GitHub\Service\GithubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class GithubProfileController extends Controller
{
    private $githubService;

    public function __construct()
    {
        $this->githubService = new GithubService();
    }

    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();
        $data = [
            'username' => $githubUser->getNickname(),
            'token' => $githubUser->token,
            'avatar' => $githubUser->getAvatar(),
        ];

        $this->githubService->saveGithubData(Auth::id(), $data);

        Inertia::location('githubProfile', $data);

        return redirect('/dashboard')->with('success', 'GitHub conectado com sucesso!');
    }
}
