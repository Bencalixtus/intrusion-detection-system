<?php

use App\Http\Controllers\NetworkEventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Security Events
    Route::get('/security-events', [NetworkEventController::class, 'index'])
        ->name('security-events.index');

    Route::get('/security-events/{networkEvent}', [NetworkEventController::class, 'show'])
        ->name('security-events.show');

    Route::patch('/security-events/{networkEvent}', [NetworkEventController::class, 'update'])
        ->name('security-events.update');
});

require __DIR__.'/auth.php';