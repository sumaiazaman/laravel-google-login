<?php

namespace Sumaia\GoogleOneTapLogin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sumaia\GoogleOneTapLogin\GoogleOneTap;

class GoogleOneTapController extends Controller
{
    protected GoogleOneTap $googleOneTap;

    public function __construct(GoogleOneTap $googleOneTap)
    {
        $this->googleOneTap = $googleOneTap;
    }

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
            $result = $this->googleOneTap->authenticate($request->credential);

            return response()->json($result);

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
