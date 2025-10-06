<?php

namespace Sumaia\GoogleOneTapLogin\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Sumaia\GoogleOneTapLogin\Tests\TestCase;

class GoogleOneTapAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_displays_correctly()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sign in with your Google account');
        $response->assertSee('g_id_onload');
        $response->assertSee('test-client-id'); // From config
    }

    public function test_dashboard_requires_authentication()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = $this->createUser([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'google_id' => '123456789',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Welcome, Test User!');
        $response->assertSee('test@example.com');
    }

    public function test_google_callback_requires_credential()
    {
        $response = $this->withoutMiddleware()
                         ->postJson('/auth/google/callback', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['credential']);
    }

    public function test_logout_redirects_to_login()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
                         ->withoutMiddleware()
                         ->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_package_routes_are_registered()
    {
        $routes = collect(\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        })->toArray();

        $this->assertContains('login', $routes);
        $this->assertContains('auth/google/callback', $routes);
        $this->assertContains('dashboard', $routes);
        $this->assertContains('logout', $routes);
    }

    public function test_package_views_are_loadable()
    {
        $this->assertTrue(view()->exists('google-onetap::auth.login'));
        $this->assertTrue(view()->exists('google-onetap::dashboard'));
    }

    public function test_package_config_is_loaded()
    {
        $this->assertEquals('test-client-id', config('google-onetap.client_id'));
        $this->assertEquals('test-client-secret', config('google-onetap.client_secret'));
    }

    protected function createUser(array $attributes = [])
    {
        $userModel = config('google-onetap.user.model', \App\Models\User::class);
        
        // Create a simple User model for testing if it doesn't exist
        if (!class_exists($userModel)) {
            $userModel = new class extends \Illuminate\Foundation\Auth\User {
                protected $table = 'users';
                protected $fillable = ['name', 'email', 'password', 'google_id', 'avatar', 'email_verified_at'];
            };
        }

        return $userModel::create(array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'google_id' => '123456789',
            'avatar' => 'https://example.com/avatar.jpg',
        ], $attributes));
    }
}
