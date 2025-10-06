<?php

/**
 * Google One Tap Login Package - Integration Example
 * 
 * This file shows how to integrate the package into an existing Laravel application.
 * Copy and modify these examples according to your needs.
 */

// ============================================================================
// 1. COMPOSER.JSON EXAMPLE
// ============================================================================

/*
Add this to your Laravel project's composer.json:

{
    "require": {
        "sumaia/google-onetap-login": "^1.0"
    }
}

Then run: composer install
*/

// ============================================================================
// 2. ENVIRONMENT CONFIGURATION (.env)
// ============================================================================

/*
Add these to your .env file:

GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
*/

// ============================================================================
// 3. USER MODEL EXAMPLE (app/Models/User.php)
// ============================================================================

/*
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',        // Add this for Google authentication
        'avatar',           // Add this for Google profile picture
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
*/

// ============================================================================
// 4. CUSTOM CONTROLLER EXAMPLE (Optional)
// ============================================================================

/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sumaia\GoogleOneTapLogin\Http\Controllers\GoogleOneTapController;

class CustomAuthController extends Controller
{
    protected $googleController;

    public function __construct(GoogleOneTapController $googleController)
    {
        $this->googleController = $googleController;
    }

    public function showLogin()
    {
        // Use package view or your custom view
        return view('auth.custom-login');
    }

    public function handleGoogleCallback(Request $request)
    {
        // Use the package controller
        $response = $this->googleController->handleCallback($request);
        
        // Add custom logic after successful login
        if ($response->getData()->success ?? false) {
            // Log the login event
            logger('User logged in via Google One Tap', [
                'user_id' => auth()->id(),
                'email' => auth()->user()->email,
            ]);
        }
        
        return $response;
    }
}
*/

// ============================================================================
// 5. CUSTOM ROUTES EXAMPLE (routes/web.php)
// ============================================================================

/*
use App\Http\Controllers\CustomAuthController;
use Illuminate\Support\Facades\Route;

// If you want to use custom controllers instead of package routes
Route::get('/login', [CustomAuthController::class, 'showLogin'])->name('login');
Route::post('/auth/google/callback', [CustomAuthController::class, 'handleGoogleCallback']);

// Or use the package routes directly (no additional routes needed)
// The package automatically registers:
// GET /login
// POST /auth/google/callback  
// GET /dashboard
// POST /logout
*/

// ============================================================================
// 6. CUSTOM VIEW EXAMPLE (resources/views/auth/custom-login.blade.php)
// ============================================================================

/*
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <div class="login-container">
        <h1>Welcome to {{ config('app.name') }}</h1>
        
        <!-- Google One Tap Integration -->
        <div id="g_id_onload"
             data-client_id="{{ config('google-onetap.client_id') }}"
             data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin"></div>
    </div>

    <script>
        function handleCredentialResponse(response) {
            fetch('/auth/google/callback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({credential: response.credential})
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.href = data.redirect || '/dashboard';
                  }
              });
        }
    </script>
</body>
</html>
*/

// ============================================================================
// 7. MIDDLEWARE EXAMPLE (Optional)
// ============================================================================

/*
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GoogleAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Custom logic before Google authentication
        if ($request->is('auth/google/*')) {
            // Log authentication attempts
            logger('Google authentication attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}
*/

// ============================================================================
// 8. EVENT LISTENER EXAMPLE (Optional)
// ============================================================================

/*
namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class GoogleLoginListener
{
    public function handle(Login $event)
    {
        $user = $event->user;
        
        // Check if this was a Google login
        if ($user->google_id) {
            // Send welcome email for new Google users
            if ($user->wasRecentlyCreated) {
                // Mail::to($user)->send(new WelcomeGoogleUser($user));
            }
            
            // Update last login timestamp
            $user->update(['last_login_at' => now()]);
        }
    }
}

// Register in EventServiceProvider:
protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\GoogleLoginListener::class,
    ],
];
*/

// ============================================================================
// 9. CONFIGURATION CUSTOMIZATION EXAMPLE
// ============================================================================

/*
After publishing config: php artisan vendor:publish --tag=google-onetap-config

Edit config/google-onetap.php:

return [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    
    // Custom routes
    'routes' => [
        'login' => '/auth/login',
        'callback' => '/auth/google/verify',
        'dashboard' => '/user/dashboard',
        'logout' => '/auth/logout',
    ],
    
    // Custom redirects
    'redirects' => [
        'after_login' => '/user/profile',
        'after_logout' => '/welcome',
    ],
    
    // Security settings
    'security' => [
        'create_users' => true,
        'update_existing_users' => true,
        'auto_verify_email' => true,
    ],
];
*/

// ============================================================================
// 10. TESTING EXAMPLE
// ============================================================================

/*
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('g_id_onload');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create(['google_id' => '123456789']);
        
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }
}
*/

echo "Google One Tap Login Package - Integration Examples\n";
echo "Copy the relevant examples above into your Laravel application.\n";
echo "Don't forget to:\n";
echo "1. Run: composer require sumaia/google-onetap-login\n";
echo "2. Publish migrations: php artisan vendor:publish --tag=google-onetap-migrations\n";
echo "3. Run migrations: php artisan migrate\n";
echo "4. Set up Google OAuth credentials in .env\n";
echo "5. Test the integration!\n";
