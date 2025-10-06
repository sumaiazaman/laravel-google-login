# Installation Guide

This guide will walk you through the complete installation and setup process for the Google One Tap Login package.

## Prerequisites

Before installing this package, make sure you have:

- PHP 8.1 or higher
- Laravel 10.x, 11.x, or 12.x
- Composer installed
- A Google Cloud Console account

## Step 1: Install the Package

Install the package via Composer:

```bash
composer require sumaia/google-onetap-login
```

The package will be automatically discovered by Laravel.

## Step 2: Publish Package Assets

### Publish Configuration (Recommended)

```bash
php artisan vendor:publish --tag=google-onetap-config
```

This creates `config/google-onetap.php` where you can customize the package behavior.

### Publish and Run Migrations

```bash
php artisan vendor:publish --tag=google-onetap-migrations
php artisan migrate
```

This adds `google_id` and `avatar` fields to your users table.

### Publish Views (Optional)

```bash
php artisan vendor:publish --tag=google-onetap-views
```

This publishes views to `resources/views/vendor/google-onetap/` for customization.

## Step 3: Google Cloud Console Setup

### 3.1 Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click "Select a project" → "New Project"
3. Enter a project name and click "Create"

### 3.2 Enable Required APIs

1. In the Google Cloud Console, go to "APIs & Services" → "Library"
2. Search for "Google+ API" and enable it

### 3.3 Create OAuth 2.0 Credentials

1. Go to "APIs & Services" → "Credentials"
2. Click "Create Credentials" → "OAuth 2.0 Client ID"
3. If prompted, configure the OAuth consent screen first:
   - Choose "External" user type
   - Fill in the required fields (App name, User support email, Developer contact)
   - Add your domain to "Authorized domains"
   - Save and continue through the scopes and test users steps

4. For the OAuth 2.0 Client ID:
   - Application type: "Web application"
   - Name: Your app name
   - Authorized JavaScript origins:
     ```
     http://localhost:8000
     https://yourdomain.com
     ```
   - Authorized redirect URIs:
     ```
     http://localhost:8000/auth/google/callback
     https://yourdomain.com/auth/google/callback
     ```

5. Click "Create" and copy the Client ID and Client Secret

## Step 4: Environment Configuration

Add the Google credentials to your `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
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

## Step 6: Test the Installation

1. Start your Laravel development server:
   ```bash
   php artisan serve
   ```

2. Visit `http://localhost:8000/login` in your browser

3. You should see the Google One Tap login interface

4. Click "Sign in with Google" to test the authentication

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
