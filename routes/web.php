<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\GitHub\GithubProfileController;
use App\Http\Controllers\Divisions\DivisionController;
use App\Http\Controllers\Divisions\LanguageDivisionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $profile = $user->githubProfile?->load('division');

    return Inertia::render('Dashboard', [
        'githubProfile' => $profile,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/github/redirect', [GithubProfileController::class, 'redirect'])->middleware('auth')->name('github.redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/leaderboard', [ProfileController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions');
    Route::get('/language-divisions', [LanguageDivisionController::class, 'index'])->name('language-divisions');
    Route::get('/user/@{githubUsername}', [ProfileController::class, 'show'])->name('user.profile');
    Route::get('/api/users/search/{query}', [ProfileController::class, 'search'])->name('users.search');
});

// GitHub callback - sem middleware auth (GitHub vai redirecionar aqui)
Route::get('/auth/github/callback', [GithubProfileController::class, 'callback'])->name('github.callback');

// Debug routes (only in production for troubleshooting)
Route::get('/debug/dashboard-data', function () {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    $profile = $user->githubProfile;
    if (!$profile) {
        return response()->json(['error' => 'No GitHub profile'], 404);
    }

    // Load division
    $profile->load('division');

    // Check what would be sent to Inertia
    return response()->json([
        'raw_profile' => [
            'id' => $profile->id,
            'github_username' => $profile->github_username,
            'score' => $profile->score,
            'top_language' => $profile->top_language,
            'language_scores_type' => gettype($profile->language_scores),
            'language_scores' => $profile->language_scores,
            'language_scores_count' => count($profile->language_scores ?? []),
        ],
        'visible_attributes' => $profile->getVisible(),
        'hidden_attributes' => $profile->getHidden(),
        'serialized_for_inertia' => $profile->toArray(),
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware(['auth', 'verified'])->name('debug.dashboard-data');

require __DIR__.'/auth.php';

