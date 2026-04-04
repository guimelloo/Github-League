<?php

namespace App\Http\Controllers\GitHub;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GitHub\Service\GithubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        \Log::info('GitHub redirect started');
        try {
            return Socialite::driver('github')->redirect();
        } catch (\Exception $e) {
            \Log::error('GitHub redirect error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Erro ao redirecionar para GitHub: ' . $e->getMessage());
        }
    }

    public function callback()
    {
        \Log::info('GitHub callback received');
        try {
            $githubUser = Socialite::driver('github')->user();
            \Log::info('GitHub user obtained: ' . $githubUser->getNickname());
            
            $data = [
                'username' => $githubUser->getNickname(),
                'token' => $githubUser->token,
                'avatar' => $githubUser->getAvatar(),
            ];

            $this->githubService->saveGithubData(Auth::id(), $data);
            \Log::info('GitHub data saved for user: ' . Auth::id());

            return redirect('/dashboard')->with('success', 'GitHub conectado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('GitHub callback error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return redirect('/dashboard')->with('error', 'Erro: ' . $e->getMessage());
        }
    }
}
