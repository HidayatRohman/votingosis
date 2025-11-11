<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\BrandingController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use App\Http\Controllers\Settings\VotingScheduleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('settings/profile/logo', [BrandingController::class, 'updateLogo'])->name('profile.logo.update');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('user-password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance.edit');

    // Branding: Logo & Favicon pages
    Route::get('settings/logo', function () {
        return Inertia::render('settings/Logo');
    })->name('logo.edit');

    Route::post('settings/logo', [BrandingController::class, 'updateLogo'])->name('logo.update');

    Route::get('settings/favicon', function () {
        return Inertia::render('settings/Favicon');
    })->name('favicon.edit');

    Route::post('settings/favicon', [BrandingController::class, 'updateFavicon'])->name('favicon.update');

    Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');

    // Voting countdown settings page
    Route::get('settings/countdown', function () {
        return Inertia::render('settings/Countdown');
    })->name('countdown.edit');

    Route::put('settings/countdown', [VotingScheduleController::class, 'update'])
        ->name('countdown.update');

    // Reset voting results and allow re-voting
    Route::post('settings/reset-voting', [VotingScheduleController::class, 'reset'])
        ->name('voting.reset');
});
