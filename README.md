# Google One Tap Login for Laravel

This repository contains the development workspace for the **sumaia/google-onetap-login** Laravel package.

## Package Overview

The `sumaia/google-onetap-login` package provides seamless Google One Tap authentication integration for Laravel applications. It allows users to sign in with their Google account using Google's One Tap sign-in feature.

## Package Installation

To use this package in your Laravel application, install it via Composer:

```bash
composer require sumaia/google-onetap-login
```

## Quick Setup Guide

### 1. Install the Package
```bash
composer require sumaia/google-onetap-login
```

### 2. Publish Configuration and Views
```bash
# Publish configuration file
php artisan vendor:publish --tag=google-onetap-config

# Publish views (optional - for customization)
php artisan vendor:publish --tag=google-onetap-views
```

### 3. Run Migration
```bash
php artisan migrate
```

### 4. Configure Google OAuth
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add your domain to authorized origins

### 5. Update Environment Variables
Add to your `.env` file:
```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
```

### 6. Update Configuration
Edit `config/google-onetap.php`:
```php
return [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    // ... other configurations
];
```

## Usage

Once installed, the package automatically registers these routes:
- `GET /login` - Login page with Google One Tap
- `POST /auth/google/callback` - Google authentication callback
- `GET /dashboard` - Protected dashboard (requires authentication)
- `POST /logout` - Logout functionality

Visit `/login` to see the Google One Tap authentication in action.

## Package Development

This repository serves as the development workspace. The actual package is located in:
```
packages/sumaia/google-onetap-login/
```

### Development Setup
```bash
# Clone this repository
git clone <repository-url>
cd google-onetap-login

# Install dependencies
composer install

# Run package tests
cd packages/sumaia/google-onetap-login
composer install
vendor/bin/phpunit
```

## Documentation

For detailed documentation, configuration options, and customization guides, see the package's README file:
[packages/sumaia/google-onetap-login/README.md](packages/sumaia/google-onetap-login/README.md)

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
