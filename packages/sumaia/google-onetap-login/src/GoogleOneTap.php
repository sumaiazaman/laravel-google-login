<?php

namespace Sumaia\GoogleOneTapLogin;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleOneTap
{
    protected GoogleClient $client;
    protected array $config;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->config = config('google-onetap');
        $this->client->setClientId($this->config['client_id']);
    }

    /**
     * Verify Google ID token and return user payload
     *
     * @param string $credential
     * @return array|false
     * @throws Exception
     */
    public function verifyToken(string $credential): array|false
    {
        try {
            $payload = $this->client->verifyIdToken($credential);
            
            if (!$payload) {
                throw new Exception('Invalid Google ID token');
            }

            return [
                'google_id' => $payload['sub'],
                'email' => $payload['email'],
                'name' => $payload['name'],
                'avatar' => $payload['picture'] ?? null,
                'email_verified' => $payload['email_verified'] ?? false,
            ];
        } catch (Exception $e) {
            throw new Exception('Token verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Find or create user based on Google credentials
     *
     * @param array $googleUser
     * @return mixed
     * @throws Exception
     */
    public function findOrCreateUser(array $googleUser)
    {
        $userModel = $this->config['user']['model'];
        $fields = $this->config['user']['fields'];

        // Find existing user by Google ID or email
        $user = $userModel::where($fields['google_id'], $googleUser['google_id'])
                          ->orWhere($fields['email'], $googleUser['email'])
                          ->first();

        if ($user) {
            return $this->updateExistingUser($user, $googleUser);
        }

        return $this->createNewUser($googleUser);
    }

    /**
     * Update existing user with Google information
     *
     * @param mixed $user
     * @param array $googleUser
     * @return mixed
     */
    protected function updateExistingUser($user, array $googleUser)
    {
        $fields = $this->config['user']['fields'];
        
        if (!$user->{$fields['google_id']} && $this->config['security']['update_existing_users']) {
            $updateData = [
                $fields['google_id'] => $googleUser['google_id'],
            ];
            
            if ($googleUser['avatar']) {
                $updateData[$fields['avatar']] = $googleUser['avatar'];
            }
            
            $user->update($updateData);
        }

        return $user;
    }

    /**
     * Create new user from Google credentials
     *
     * @param array $googleUser
     * @return mixed
     * @throws Exception
     */
    protected function createNewUser(array $googleUser)
    {
        if (!$this->config['security']['create_users']) {
            throw new Exception('User creation is not allowed');
        }

        $userModel = $this->config['user']['model'];
        $fields = $this->config['user']['fields'];

        $userData = [
            $fields['name'] => $googleUser['name'],
            $fields['email'] => $googleUser['email'],
            $fields['google_id'] => $googleUser['google_id'],
            'password' => Hash::make(Str::random(32)), // Random password
        ];

        if ($googleUser['avatar']) {
            $userData[$fields['avatar']] = $googleUser['avatar'];
        }

        if ($this->config['security']['auto_verify_email']) {
            $userData[$fields['email_verified_at']] = now();
        }

        return $userModel::create($userData);
    }

    /**
     * Authenticate user with Google One Tap
     *
     * @param string $credential
     * @return array
     * @throws Exception
     */
    public function authenticate(string $credential): array
    {
        // Verify the Google ID token
        $googleUser = $this->verifyToken($credential);
        
        // Find or create the user
        $user = $this->findOrCreateUser($googleUser);
        
        if (!$user) {
            throw new Exception('User authentication failed');
        }

        // Log the user in
        Auth::login($user);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'Successfully authenticated with Google One Tap',
            'redirect' => $this->config['redirects']['after_login'] ?? '/dashboard'
        ];
    }

    /**
     * Get the Google Client ID from configuration
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->config['client_id'] ?? '';
    }

    /**
     * Get One Tap configuration for frontend
     *
     * @return array
     */
    public function getOneTapConfig(): array
    {
        return $this->config['one_tap'] ?? [];
    }
}
