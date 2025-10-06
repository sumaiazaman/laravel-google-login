# Usage Examples

This document provides practical examples of how to use the Google One Tap Login package in different scenarios.

## Basic Usage

### 1. Default Implementation

After installation, the package works out of the box:

```php
// Visit /login - shows Google One Tap interface
// Visit /dashboard - shows user dashboard (requires auth)
// POST /logout - logs out the user
```

### 2. Using in Blade Templates

```blade
{{-- Check if user is authenticated --}}
@auth
    <div class="user-info">
        <img src="{{ auth()->user()->avatar }}" alt="Avatar">
        <span>Welcome, {{ auth()->user()->name }}!</span>
        <form action="{{ route('google-onetap.logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
@else
    <a href="{{ route('google-onetap.login') }}" class="btn btn-primary">
        Login with Google
    </a>
@endauth
```

### 3. Custom Login Button

```blade
{{-- Custom styled login button --}}
<div class="custom-login">
    <h2>Sign In</h2>
    <div id="g_id_onload"
         data-client_id="{{ config('google-onetap.client_id') }}"
         data-callback="handleCredentialResponse">
    </div>
    <div class="g_id_signin" data-type="standard"></div>
</div>

<script>
function handleCredentialResponse(response) {
    fetch('{{ route("google-onetap.callback") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({credential: response.credential})
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              window.location.href = data.redirect;
          }
      });
}
</script>
```

## Advanced Usage

### 1. Custom User Model

```php
// config/google-onetap.php
return [
    'user' => [
        'model' => App\Models\CustomUser::class,
        'fields' => [
            'google_id' => 'google_user_id',
            'name' => 'full_name',
            'email' => 'email_address',
            'avatar' => 'profile_image',
            'email_verified_at' => 'verified_at',
        ],
    ],
];
```

### 2. Custom Routes

```php
// config/google-onetap.php
return [
    'routes' => [
        'login' => '/auth/login',
        'callback' => '/auth/google/verify',
        'dashboard' => '/user/profile',
        'logout' => '/auth/logout',
    ],
    'route_names' => [
        'login' => 'auth.login',
        'callback' => 'auth.google.verify',
        'dashboard' => 'user.profile',
        'logout' => 'auth.logout',
    ],
];
```

### 3. Custom Middleware

```php
// config/google-onetap.php
return [
    'middleware' => [
        'web' => ['web', 'throttle:60,1'],
        'auth' => ['web', 'auth', 'verified'],
        'guest' => ['web', 'guest'],
    ],
];
```

### 4. Disable User Creation

```php
// config/google-onetap.php
return [
    'security' => [
        'create_users' => false,
        'update_existing_users' => true,
        'verify_email' => true,
        'auto_verify_email' => false,
    ],
];
```

## Integration Examples

### 1. With Laravel Breeze

```php
// routes/web.php
use Illuminate\Support\Facades\Route;

// Remove or comment out Breeze login route
// Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// The package will handle the login route
// Keep other Breeze routes as needed
```

### 2. With Laravel Jetstream

```php
// config/google-onetap.php
return [
    'routes' => [
        'login' => '/login',
        'callback' => '/auth/google/callback',
        'dashboard' => '/dashboard', // Jetstream dashboard
        'logout' => '/logout',
    ],
];
```

### 3. API Integration

```php
// Custom controller for API usage
use Sumaia\GoogleOneTapLogin\Http\Controllers\GoogleOneTapController;

class ApiAuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $controller = new GoogleOneTapController();
        $response = $controller->handleCallback($request);
        
        if ($response->getData()->success) {
            $user = auth()->user();
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }
        
        return $response;
    }
}
```

### 4. Multi-Guard Authentication

```php
// config/google-onetap.php
return [
    'middleware' => [
        'web' => ['web'],
        'auth' => ['web', 'auth:admin'], // Use admin guard
        'guest' => ['web', 'guest:admin'],
    ],
];
```

## Event Handling

### 1. Listen for User Creation

```php
// app/Providers/EventServiceProvider.php
use Illuminate\Auth\Events\Registered;

protected $listen = [
    Registered::class => [
        SendEmailVerificationNotification::class,
        // Add your custom listener
        WelcomeNewGoogleUser::class,
    ],
];
```

### 2. Custom User Creation Logic

```php
// Create a custom service provider
class GoogleAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Override the default user creation
        $this->app->bind(GoogleOneTapController::class, CustomGoogleController::class);
    }
}
```

## Testing

### 1. Feature Tests

```php
public function test_google_login_creates_user()
{
    // Mock Google response
    $this->mock(GoogleClient::class, function ($mock) {
        $mock->shouldReceive('verifyIdToken')
             ->andReturn([
                 'sub' => '123456789',
                 'email' => 'test@example.com',
                 'name' => 'Test User',
                 'picture' => 'https://example.com/avatar.jpg',
             ]);
    });

    $response = $this->postJson('/auth/google/callback', [
        'credential' => 'fake-jwt-token'
    ]);

    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'google_id' => '123456789',
    ]);
}
```

### 2. Unit Tests

```php
public function test_config_values_are_correct()
{
    config(['google-onetap.client_id' => 'test-id']);
    
    $this->assertEquals('test-id', config('google-onetap.client_id'));
}
```

## Customization

### 1. Custom Views

After publishing views, customize them:

```blade
{{-- resources/views/vendor/google-onetap/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Your custom login design --}}
            <div id="g_id_onload" data-client_id="{{ config('google-onetap.client_id') }}"></div>
            <div class="g_id_signin"></div>
        </div>
    </div>
</div>
@endsection
```

### 2. Custom Styling

```css
/* Custom CSS for Google One Tap button */
.g_id_signin {
    margin: 20px auto;
    width: fit-content;
}

.custom-login-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
```

This covers the most common usage scenarios. For more advanced customization, refer to the package configuration file and source code.
