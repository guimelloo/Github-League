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
        try {
            \Log::info('GitHub OAuth callback started for user ' . Auth::id());
            
            $githubUser = Socialite::driver('github')->user();
            \Log::info('GitHub user retrieved: ' . $githubUser->getNickname());

            if (!$githubUser) {
                throw new \Exception('Failed to retrieve GitHub user data');
            }

            $data = [
                'username' => $githubUser->getNickname(),
                'token' => $githubUser->token,
                'avatar' => $githubUser->getAvatar(),
            ];

            \Log::info('Saving GitHub data for user ' . Auth::id());
            $this->githubService->saveGithubData(Auth::id(), $data);

            \Log::info('GitHub data saved successfully for user ' . Auth::id());

            Inertia::location('githubProfile', $data);

            return redirect('/dashboard')->with('success', 'GitHub conectado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('GitHub OAuth callback error for user ' . Auth::id() . ': ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect('/dashboard')->with('error', 'Erro ao conectar com GitHub: ' . $e->getMessage());
        }
    }
}
