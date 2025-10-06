# Installation Guide

This comprehensive guide will walk you through the complete installation and setup process for the Google One Tap Login package.

## Prerequisites

Before installing this package, ensure you have:

- **PHP**: 8.1 or higher
- **Laravel**: 10.x, 11.x, or 12.x
- **Composer**: Latest version installed
- **Google Cloud Console**: Account with project creation access
- **Database**: MySQL, PostgreSQL, SQLite, or SQL Server
- **Web Server**: Apache, Nginx, or Laravel's built-in server

## Step 1: Install the Package

### 1.1 Install via Composer

Install the package using Composer in your Laravel project root:

```bash
composer require sumaia/google-onetap-login
```

The package will be automatically discovered by Laravel thanks to auto-discovery.

### 1.2 Verify Installation

Verify the package is installed correctly by checking if the service provider is loaded:

```bash
php artisan package:discover
```

You should see `Sumaia\GoogleOneTapLogin\Providers\GoogleOneTapServiceProvider` in the discovered providers list.

### 1.3 Check Package Version

Confirm the installed version:

```bash
composer show sumaia/google-onetap-login
```

## Step 2: Publish Package Assets

### 2.1 Publish Configuration (Required)

Publish the configuration file to customize package behavior:

```bash
php artisan vendor:publish --tag=google-onetap-config
```

This creates `config/google-onetap.php` where you can customize:
- Google OAuth credentials
- User model and field mappings
- Security settings
- UI customization options
- Route configurations

### 2.2 Publish and Run Migrations (Required)

Publish the database migrations and run them:

```bash
php artisan vendor:publish --tag=google-onetap-migrations
php artisan migrate
```

This adds the following fields to your users table:
- `google_id` (string, nullable) - Stores Google user ID
- `avatar` (string, nullable) - Stores Google profile picture URL

**Important Notes:**
- If you already have these fields, you can skip the migration or modify it accordingly
- The migration is designed to be non-destructive and won't affect existing data
- You can customize field names in the configuration file

### 2.3 Publish Views (Optional)

Publish views for customization:

```bash
php artisan vendor:publish --tag=google-onetap-views
```

This publishes views to `resources/views/vendor/google-onetap/` including:
- Login page template
- Dashboard template
- Google One Tap button component

### 2.4 Publish All Assets at Once

Alternatively, publish everything at once:

```bash
php artisan vendor:publish --provider="Sumaia\GoogleOneTapLogin\Providers\GoogleOneTapServiceProvider"
```

### 2.5 Clear Caches

After publishing, clear Laravel caches:

```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Step 3: Google Cloud Console Setup

### 3.1 Create a Google Cloud Project

1. **Navigate to Google Cloud Console**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Sign in with your Google account

2. **Create New Project**
   - Click "Select a project" dropdown → "New Project"
   - Enter a descriptive project name (e.g., "My Laravel App - Google Auth")
   - Select your organization (if applicable)
   - Click "Create" and wait for project creation

3. **Select Your Project**
   - Ensure your new project is selected in the project dropdown

### 3.2 Enable Required APIs

1. **Navigate to APIs & Services**
   - In the Google Cloud Console, go to "APIs & Services" → "Library"

2. **Enable Google+ API**
   - Search for "Google+ API"
   - Click on it and click "Enable"
   - Wait for the API to be enabled

### 3.3 Configure OAuth Consent Screen

Before creating credentials, you must configure the OAuth consent screen:

1. **Go to OAuth Consent Screen**
   - Navigate to "APIs & Services" → "OAuth consent screen"

2. **Choose User Type**
   - Select "External" (for public apps) or "Internal" (for G Suite organizations)
   - Click "Create"

3. **Fill App Information**
   - **App name**: Your application name
   - **User support email**: Your email address
   - **App logo**: Upload your app logo (optional)
   - **App domain**: Your website domain
   - **Authorized domains**: Add your domains (e.g., `yourdomain.com`)
   - **Developer contact information**: Your email address

4. **Configure Scopes**
   - Click "Add or Remove Scopes"
   - Add these scopes:
     - `../auth/userinfo.email`
     - `../auth/userinfo.profile`
     - `openid`
   - Click "Update"

5. **Add Test Users** (for External apps in testing)
   - Add email addresses of users who can test your app
   - Click "Save and Continue"

### 3.4 Create OAuth 2.0 Credentials

1. **Navigate to Credentials**
   - Go to "APIs & Services" → "Credentials"

2. **Create OAuth 2.0 Client ID**
   - Click "Create Credentials" → "OAuth 2.0 Client ID"

3. **Configure Application Type**
   - **Application type**: "Web application"
   - **Name**: Descriptive name (e.g., "Laravel Google One Tap")

4. **Set Authorized JavaScript Origins**
   Add all domains where your app will run:
   ```
   http://localhost:8000
   http://127.0.0.1:8000
   https://yourdomain.com
   https://www.yourdomain.com
   ```

5. **Set Authorized Redirect URIs**
   Add callback URLs for your app:
   ```
   http://localhost:8000/auth/google/callback
   http://127.0.0.1:8000/auth/google/callback
   https://yourdomain.com/auth/google/callback
   https://www.yourdomain.com/auth/google/callback
   ```

6. **Create and Save Credentials**
   - Click "Create"
   - **Important**: Copy and securely store the Client ID and Client Secret
   - Download the JSON file for backup

## Step 4: Environment Configuration

Add the Google credentials to your `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
```

### Clear Configuration Cache

After updating your `.env` file, clear the configuration cache:

```bash
php artisan config:clear
```

## Step 5: Update User Model

Ensure your User model includes the Google fields in the `$fillable` array:

```php
// app/Models/User.php
protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',        // Add this
    'avatar',           // Add this
    'email_verified_at',
];
```

## Step 6: Integration Options

Choose one of these integration methods:

### Option A: Use Built-in Routes (Easiest)

The package automatically provides `/login`, `/dashboard`, and logout routes. Just visit `/login` to see Google One Tap in action.

### Option B: Use Blade Component in Your Views

Add Google One Tap to any view:

```blade
{{-- Simple integration --}}
<x-google-onetap-button />

{{-- With custom options --}}
<x-google-onetap-button
    :auto-prompt="true"
    button-type="standard"
    button-theme="filled_blue"
    button-size="large" />
```

### Option C: Use Blade Directive

```blade
{{-- One-line integration --}}
@googleOneTap
```

### Option D: Custom Implementation

Use the GoogleOneTap service in your controllers:

```php
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

## Step 7: Test the Installation

### 7.1 Start Development Server

Start your Laravel development server:

```bash
php artisan serve
```

Your application will be available at `http://localhost:8000` or `http://127.0.0.1:8000`.

### 7.2 Test Google One Tap Interface

1. **Visit Login Page**
   - Navigate to `http://localhost:8000/login` in your browser

2. **Verify Google One Tap Modal**
   - The Google One Tap modal should automatically appear in the top-right corner
   - If you're signed into Google, you should see your account(s) listed

3. **Test Authentication**
   - Click on your Google account in the modal
   - You should be redirected to the dashboard upon successful authentication

### 7.3 Test Fallback Button

If the modal doesn't appear:
1. Look for the "Sign in with Google" button on the login page
2. Click it to test the authentication flow
3. Complete the Google OAuth flow

### 7.4 Verify Dashboard Access

After successful authentication:
1. You should be redirected to `/dashboard`
2. Your Google profile information should be displayed
3. Your user record should be created in the database

### 7.5 Test Logout

1. Click the logout button on the dashboard
2. You should be redirected back to the login page
3. Your session should be cleared

## Troubleshooting

### Common Issues

**Issue: "Invalid token" error**
- Solution: Verify your Google Client ID is correct in the `.env` file
- Check that your domain is added to authorized origins in Google Console

**Issue: Routes not found**
- Solution: Clear your route cache: `php artisan route:clear`
- Make sure the package service provider is registered

**Issue: Views not loading**
- Solution: Clear your view cache: `php artisan view:clear`
- Publish the views if you need to customize them

**Issue: Migration errors**
- Solution: Check if the `google_id` and `avatar` columns already exist
- You may need to modify the migration if your users table structure is different

**Issue: Google One Tap not appearing**
- Solution: Check browser console for JavaScript errors
- Verify the Google Client ID is correctly set
- Make sure your domain is authorized in Google Console

### Getting Help

If you encounter issues:

1. Check the [README.md](README.md) for additional configuration options
2. Review the [troubleshooting section](README.md#troubleshooting)
3. Open an issue on [GitHub](https://github.com/sumaia/google-onetap-login/issues)

## Next Steps

After successful installation:

1. Customize the configuration in `config/google-onetap.php`
2. Customize the views if needed
3. Set up your production Google OAuth credentials
4. Test thoroughly in your staging environment

Congratulations! You now have Google One Tap authentication working in your Laravel application.
