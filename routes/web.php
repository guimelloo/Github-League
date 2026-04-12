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
    return Inertia::render('Dashboard', [
        'githubProfile' => $user->githubProfile?->load('division'),
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

require __DIR__.'/auth.php';

