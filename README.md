# Google One Tap Login for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sumaia/google-onetap-login.svg?style=flat-square)](https://packagist.org/packages/sumaia/google-onetap-login)
[![Total Downloads](https://img.shields.io/packagist/dt/sumaia/google-onetap-login.svg?style=flat-square)](https://packagist.org/packages/sumaia/google-onetap-login)
[![License](https://img.shields.io/packagist/l/sumaia/google-onetap-login.svg?style=flat-square)](https://packagist.org/packages/sumaia/google-onetap-login)

A Laravel package that provides seamless Google One Tap authentication integration. This package allows users to sign in to your Laravel application with just one tap using their Google account, providing a frictionless authentication experience.

## Features

- 🚀 **Google One Tap** - Automatic modal popup for seamless sign-in experience
- 🔐 **Secure Authentication** - JWT token verification using Google's official API
- 🎨 **Customizable UI** - Publishable views that you can customize
- ⚙️ **Configurable** - Extensive configuration options
- 📱 **Responsive Design** - Works perfectly on all devices
- 🧪 **Well Tested** - Comprehensive test suite included
- 📚 **Well Documented** - Clear documentation and examples
- 🔄 **Auto-Prompt** - Automatic Google One Tap modal appears without user interaction

## Requirements

- PHP 8.1 or higher
- Laravel 10.x, 11.x, or 12.x
- Google Cloud Console project with OAuth 2.0 credentials

## Installation

### 1. Install the Package

```bash
composer require sumaia/google-onetap-login
```

The package will be automatically discovered by Laravel.

### 2. Publish Configuration (Required)

```bash
php artisan vendor:publish --tag=google-onetap-config
```

This creates `config/google-onetap.php` for customization.

### 3. Publish and Run Migrations (Required)

```bash
php artisan vendor:publish --tag=google-onetap-migrations
php artisan migrate
```

This adds `google_id` and `avatar` fields to your users table.

### 4. Publish Views (Optional)

```bash
php artisan vendor:publish --tag=google-onetap-views
```

### 5. Verify Installation

After installation, verify everything is working correctly:

📋 **[Installation Verification Guide](INSTALLATION_VERIFICATION.md)** - Complete checklist and troubleshooting

## Configuration

### 1. Google Cloud Console Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API
4. Create OAuth 2.0 credentials:
   - Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client ID"
   - Application type: "Web application"
   - Add authorized JavaScript origins:
     ```
     http://localhost:8000
     https://yourdomain.com
     ```
   - Add authorized redirect URIs:
     ```
     http://localhost:8000/auth/google/callback
     https://yourdomain.com/auth/google/callback
     ```

### 2. Environment Variables

Add these variables to your `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
```

### 3. User Model Setup

Ensure your User model includes the Google fields in the `$fillable` array:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',
    'avatar',
    'email_verified_at',
];
```

## How Google One Tap Works

Google One Tap provides a seamless sign-in experience by automatically displaying a modal popup when users visit your login page. Here's what happens:

1. **Automatic Modal**: When users visit `/login`, a Google One Tap modal automatically appears
2. **User Selection**: Users can select their Google account from the modal
3. **Instant Authentication**: No redirects - authentication happens instantly in the popup
4. **Fallback Button**: If the modal doesn't appear, users can click the "Sign in with Google" button

### Visual Example

The Google One Tap modal looks like this:

```
┌─────────────────────────────────────┐
│  🔵 Sign in as John Doe             │
│  john.doe@gmail.com                 │
│  ┌─────────────────────────────────┐ │
│  │        Continue as John         │ │
│  └─────────────────────────────────┘ │
└─────────────────────────────────────┘
```

## Usage

### Basic Usage

Once installed and configured, the package automatically provides these routes:

- `GET /login` - Login page with Google One Tap modal
- `POST /auth/google/callback` - Handles Google authentication
- `GET /dashboard` - Protected dashboard (requires authentication)
- `POST /logout` - User logout

### Easy Integration Options

The package provides multiple ways to integrate Google One Tap into your application:

#### 1. Using the Blade Component (Recommended)

```blade
{{-- In any Blade template --}}
<x-google-onetap-button />

{{-- With custom options --}}
<x-google-onetap-button
    :auto-prompt="true"
    button-type="standard"
    button-theme="filled_blue"
    button-size="large" />
```

#### 2. Using the Blade Directive

```blade
{{-- Simple one-line integration --}}
@googleOneTap
```

#### 3. Using the Service Class

```php
// In your controller
use Sumaia\GoogleOneTapLogin\GoogleOneTap;

class AuthController extends Controller
{
    public function handleGoogleAuth(Request $request, GoogleOneTap $googleOneTap)
    {
        try {
            $result = $googleOneTap->authenticate($request->credential);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

### Custom Routes

You can customize the routes by publishing the configuration file and modifying the `routes` section:

```php
'routes' => [
    'login' => '/custom-login',
    'callback' => '/auth/google/custom-callback',
    'dashboard' => '/custom-dashboard',
    'logout' => '/custom-logout',
],
```

### Custom Views

Publish the views and customize them according to your needs:

```bash
php artisan vendor:publish --tag=google-onetap-views
```

Views will be published to `resources/views/vendor/google-onetap/`.

### Configuration Options

The package provides extensive configuration options. Publish the config file to customize:

```bash
php artisan vendor:publish --tag=google-onetap-config
```

Key configuration options include:

- **User Model**: Specify which User model to use
- **Field Mapping**: Map Google data to your user fields
- **Security Settings**: Control user creation and updates
- **UI Customization**: Customize Google One Tap appearance
- **Redirects**: Set custom redirect URLs

## Advanced Usage

### Custom User Model

If you're using a custom User model, update the configuration:

```php
'user' => [
    'model' => App\Models\CustomUser::class,
    'fields' => [
        'google_id' => 'google_id',
        'name' => 'full_name',
        'email' => 'email_address',
        'avatar' => 'profile_picture',
        'email_verified_at' => 'email_verified_at',
    ],
],
```

### Custom Middleware

You can specify custom middleware for different route groups:

```php
'middleware' => [
    'web' => ['web', 'custom-middleware'],
    'auth' => ['web', 'auth', 'verified'],
    'guest' => ['web', 'guest'],
],
```

### Disable User Creation

To prevent automatic user creation:

```php
'security' => [
    'create_users' => false,
    'update_existing_users' => true,
],
```

## Testing

Run the package tests:

```bash
composer test
```

## Troubleshooting

### Common Issues

1. **"Invalid token" error**
   - Verify your Google Client ID is correct
   - Check that your domain is added to authorized origins in Google Console

2. **Routes not working**
   - Clear your route cache: `php artisan route:clear`
   - Make sure the package is properly installed

3. **Views not loading**
   - Publish the views: `php artisan vendor:publish --tag=google-onetap-views`
   - Clear your view cache: `php artisan view:clear`

4. **Migration errors**
   - Make sure you've published the migrations
   - Check if the columns already exist in your users table

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email security@sumaia.dev instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Sumaia](https://github.com/sumaia)
- [All Contributors](../../contributors)

## Examples

### Basic Implementation

After installation, users can immediately use Google One Tap login by visiting `/login`:

```php
// The package automatically handles everything!
// Users just need to click the Google sign-in button
```

### Custom Controller Integration

You can also use the package in your own controllers:

```php
use Sumaia\GoogleOneTapLogin\Http\Controllers\GoogleOneTapController;

class AuthController extends Controller
{
    public function showCustomLogin()
    {
        return view('google-onetap::auth.login');
    }
}
```

### Checking Authentication Status

```php
// In your blade templates
@auth
    <p>Welcome, {{ auth()->user()->name }}!</p>
@else
    <a href="{{ route('google-onetap.login') }}">Login with Google</a>
@endauth
```

## API Reference

### Available Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/login` | `google-onetap.login` | Show login page |
| POST | `/auth/google/callback` | `google-onetap.callback` | Handle Google auth |
| GET | `/dashboard` | `google-onetap.dashboard` | Show dashboard |
| POST | `/logout` | `google-onetap.logout` | Logout user |

### Configuration Keys

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `client_id` | string | `env('GOOGLE_CLIENT_ID')` | Google OAuth Client ID |
| `client_secret` | string | `env('GOOGLE_CLIENT_SECRET')` | Google OAuth Client Secret |
| `routes.login` | string | `/login` | Login route path |
| `security.create_users` | boolean | `true` | Allow automatic user creation |

## Support

If you find this package helpful, please consider giving it a ⭐ on GitHub!

For support, please open an issue on GitHub or contact us at support@sumaia.com.
