<?php

use Illuminate\Support\Facades\Route;
use Sumaia\GoogleOneTapLogin\Http\Controllers\GoogleOneTapController;

/*
|--------------------------------------------------------------------------
| Google One Tap Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle Google One Tap authentication for your application.
| They are automatically loaded by the GoogleOneTapServiceProvider.
|
*/

// Guest routes (for unauthenticated users)
Route::middleware(config('google-onetap.middleware.guest', ['web', 'guest']))
    ->group(function () {
        
        // Login page
        Route::get(
            config('google-onetap.routes.login', '/login'),
            [GoogleOneTapController::class, 'showLogin']
        )->name('login'); // Use 'login' as primary name for Laravel compatibility
        
        // Google One Tap callback
        Route::post(
            config('google-onetap.routes.callback', '/auth/google/callback'),
            [GoogleOneTapController::class, 'handleCallback']
        )->name(config('google-onetap.route_names.callback', 'google-onetap.callback'));
        
    });

// Authenticated routes (for logged-in users)
Route::middleware(config('google-onetap.middleware.auth', ['web', 'auth']))
    ->group(function () {
        
        // Dashboard
        Route::get(
            config('google-onetap.routes.dashboard', '/dashboard'),
            [GoogleOneTapController::class, 'showDashboard']
        )->name(config('google-onetap.route_names.dashboard', 'google-onetap.dashboard'));
        
        // Logout
        Route::post(
            config('google-onetap.routes.logout', '/logout'),
            [GoogleOneTapController::class, 'logout']
        )->name(config('google-onetap.route_names.logout', 'google-onetap.logout'));
        
    });
