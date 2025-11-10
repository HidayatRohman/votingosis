<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    // Ambil sebagian data kandidat untuk preview di beranda
    $items = \App\Models\Voting::query()->select(['id', 'nama', 'nip', 'kelas', 'jurusan', 'foto'])
        ->withCount('votes')
        ->orderBy('nama')
        ->limit(8)
        ->get();

    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
        'previewCandidates' => $items,
    ]);
})->name('home');

Route::get('dashboard', function () {
    $items = \App\Models\Voting::query()->select(['id', 'nama', 'nip', 'kelas', 'jurusan', 'foto'])
        ->withCount('votes')
        ->orderBy('nama')
        ->get();

    $totalVotes = (int) \App\Models\Vote::query()->count();

    return Inertia::render('Dashboard', [
        'stats' => [
            'items' => $items,
            'totalVotes' => $totalVotes,
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Voting CRUD routes
Route::middleware(['auth'])->group(function () {
    Route::resource('voting', \App\Http\Controllers\VotingController::class);
    // Users CRUD routes
    Route::resource('users', \App\Http\Controllers\UserController::class);
    // Kandidat list page
    Route::get('candidates', [\App\Http\Controllers\VotingController::class, 'candidates'])->name('candidates.index');
    // Vote action (satu kali vote per user)
    Route::post('vote/{voting}', [\App\Http\Controllers\VotingController::class, 'vote'])->name('vote.store');
});

require __DIR__.'/settings.php';
