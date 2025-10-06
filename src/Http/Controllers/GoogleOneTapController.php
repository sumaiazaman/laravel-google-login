<?php

namespace Sumaia\GoogleOneTapLogin\Http\Controllers;

use Illuminate\Routing\Controller;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleOneTapController extends Controller
{
    /**
     * Show the login page with Google One Tap
     */
    public function showLogin()
    {
        return view('google-onetap::auth.login');
    }

    /**
     * Handle Google One Tap authentication
     */
    public function handleCallback(Request $request)
    {
        $request->validate([
            'credential' => 'required|string',
        ]);

        try {
            // Initialize Google Client
            $client = new GoogleClient();
            $client->setClientId(config('google-onetap.client_id'));

            // Verify the ID token
            $payload = $client->verifyIdToken($request->credential);
            
            if (!$payload) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Extract user information
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];
            $avatar = $payload['picture'] ?? null;

            // Get user model class from config
            $userModel = config('google-onetap.user.model');
            $fields = config('google-onetap.user.fields');

            // Find or create user
            $user = $userModel::where($fields['google_id'], $googleId)
                              ->orWhere($fields['email'], $email)
                              ->first();

            if ($user) {
                // Update existing user with Google ID if not set
                if (!$user->{$fields['google_id']} && config('google-onetap.security.update_existing_users')) {
                    $updateData = [
                        $fields['google_id'] => $googleId,
                    ];
                    
                    if ($avatar) {
                        $updateData[$fields['avatar']] = $avatar;
                    }
                    
                    $user->update($updateData);
                }
            } else {
                // Create new user if allowed
                if (config('google-onetap.security.create_users')) {
                    $userData = [
                        $fields['name'] => $name,
                        $fields['email'] => $email,
                        $fields['google_id'] => $googleId,
                        'password' => Hash::make(Str::random(32)), // Random password
                    ];

                    if ($avatar) {
                        $userData[$fields['avatar']] = $avatar;
                    }

                    if (config('google-onetap.security.auto_verify_email')) {
                        $userData[$fields['email_verified_at']] = now();
                    }

                    $user = $userModel::create($userData);
                }
            }

            if (!$user) {
                return response()->json(['error' => 'User creation not allowed'], 403);
            }

            // Log the user in
            Auth::login($user);

            $redirectUrl = config('google-onetap.redirects.after_login', '/dashboard');

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged in',
                'user' => $user,
                'redirect' => $redirectUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the dashboard
     */
    public function showDashboard()
    {
        return view('google-onetap::dashboard');
    }

    /**
     * Handle user logout
     */
    public function logout()
    {
        Auth::logout();
        
        $redirectUrl = config('google-onetap.redirects.after_logout', '/login');
        
        return redirect($redirectUrl);
    }
}
