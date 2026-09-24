<?php

use App\Http\Controllers\Api\NetworkEventApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NetworkEventController;
use App\Http\Controllers\ProfileController;
use App\Models\NetworkEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Security Events
    |--------------------------------------------------------------------------
    */

    Route::get('/security-events', [NetworkEventController::class, 'index'])
        ->name('security-events.index');

    Route::get('/security-events/{networkEvent}', [NetworkEventController::class, 'show'])
        ->name('security-events.show');

    Route::patch('/security-events/{networkEvent}', [NetworkEventController::class, 'update'])
        ->name('security-events.update');


    /*
    |--------------------------------------------------------------------------
    | Dashboard Statistics API
    |--------------------------------------------------------------------------
    |
    | Returns the latest IDS statistics for the dashboard.
    |
    */

    Route::get('/dashboard/stats', function (): JsonResponse {

        return response()->json([

            'totalEvents' => NetworkEvent::count(),

            'unresolvedEvents' => NetworkEvent::where(
                'status',
                'unresolved'
            )->count(),

            'investigatingEvents' => NetworkEvent::where(
                'status',
                'investigating'
            )->count(),

            'resolvedEvents' => NetworkEvent::where(
                'status',
                'resolved'
            )->count(),

            'highSeverity' => NetworkEvent::where(
                'severity',
                'high'
            )->count(),

            'mediumSeverity' => NetworkEvent::where(
                'severity',
                'medium'
            )->count(),

            'lowSeverity' => NetworkEvent::where(
                'severity',
                'low'
            )->count(),

        ]);

    })->name('dashboard.stats');


    /*
    |--------------------------------------------------------------------------
    | Recent Dashboard Events API
    |--------------------------------------------------------------------------
    |
    | Returns the five most recent security events.
    |
    */

    Route::get('/dashboard/recent-events', function (): JsonResponse {

        $events = NetworkEvent::orderByDesc('detected_at')
            ->orderByDesc('id')
            ->take(5)
            ->get([
                'id',
                'source_ip',
                'event_type',
                'severity',
                'status',
                'detected_at',
            ]);

        return response()->json([
            'events' => $events,
        ]);

    })->name('dashboard.recent-events');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';