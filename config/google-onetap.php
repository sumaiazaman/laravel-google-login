<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google OAuth Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Google OAuth credentials. You can obtain
    | these credentials from the Google Cloud Console by creating OAuth 2.0
    | client credentials for your application.
    |
    */

    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the routes used by the Google One Tap authentication.
    | You can customize the route names and paths according to your needs.
    |
    */

    'routes' => [
        'login' => '/login',
        'callback' => '/auth/google/callback',
        'dashboard' => '/dashboard',
        'logout' => '/logout',
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Names
    |--------------------------------------------------------------------------
    |
    | Configure the route names used by the package. These names are used
    | for generating URLs and redirects within the package.
    |
    */

    'route_names' => [
        'login' => 'google-onetap.login',
        'callback' => 'google-onetap.callback',
        'dashboard' => 'google-onetap.dashboard',
        'logout' => 'google-onetap.logout',
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the middleware that should be applied to the package routes.
    | You can add or remove middleware as needed for your application.
    |
    */

    'middleware' => [
        'web' => ['web'],
        'auth' => ['web', 'auth'],
        'guest' => ['web', 'guest'],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the User model and the fields that should be used for
    | storing Google authentication data.
    |
    */

    'user' => [
        'model' => App\Models\User::class,
        'fields' => [
            'google_id' => 'google_id',
            'name' => 'name',
            'email' => 'email',
            'avatar' => 'avatar',
            'email_verified_at' => 'email_verified_at',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where users should be redirected after successful login
    | and logout operations.
    |
    */

    'redirects' => [
        'after_login' => '/dashboard',
        'after_logout' => '/login',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google One Tap UI Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the appearance and behavior of the Google One Tap prompt.
    | These settings control how the One Tap UI is displayed to users.
    |
    */

    'one_tap' => [
        'auto_prompt' => true,
        'context' => 'signin',
        'ux_mode' => 'popup',
        'cancel_on_tap_outside' => false,
        'button' => [
            'type' => 'standard',
            'shape' => 'rectangular',
            'theme' => 'outline',
            'text' => 'signin_with',
            'size' => 'large',
            'logo_alignment' => 'left',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configure security-related settings for the Google One Tap authentication.
    |
    */

    'security' => [
        'verify_email' => true,
        'auto_verify_email' => true,
        'create_users' => true,
        'update_existing_users' => true,
    ],

];
