<?php

namespace Sumaia\GoogleOneTapLogin\Tests\Unit;

use Sumaia\GoogleOneTapLogin\Tests\TestCase;
use Sumaia\GoogleOneTapLogin\GoogleOneTap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleOneTapServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GoogleOneTap $googleOneTap;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->googleOneTap = new GoogleOneTap();
        
        // Create users table for testing
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /** @test */
    public function it_can_get_client_id_from_config()
    {
        $clientId = $this->googleOneTap->getClientId();
        
        $this->assertEquals('test-client-id', $clientId);
    }

    /** @test */
    public function it_can_get_one_tap_config()
    {
        $config = $this->googleOneTap->getOneTapConfig();
        
        $this->assertIsArray($config);
        $this->assertArrayHasKey('auto_prompt', $config);
        $this->assertArrayHasKey('context', $config);
        $this->assertArrayHasKey('ux_mode', $config);
    }

    /** @test */
    public function it_can_create_new_user_from_google_data()
    {
        // Enable user creation in config
        config(['google-onetap.security.create_users' => true]);
        config(['google-onetap.security.auto_verify_email' => true]);
        
        $googleUser = [
            'google_id' => '123456789',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/avatar.jpg',
            'email_verified' => true,
        ];

        $user = $this->googleOneTap->findOrCreateUser($googleUser);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('123456789', $user->google_id);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('https://example.com/avatar.jpg', $user->avatar);
        $this->assertNotNull($user->email_verified_at);
    }

    /** @test */
    public function it_can_find_existing_user_by_google_id()
    {
        // Create existing user
        $existingUser = User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'google_id' => '123456789',
            'password' => bcrypt('password'),
        ]);

        $googleUser = [
            'google_id' => '123456789',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/avatar.jpg',
            'email_verified' => true,
        ];

        $user = $this->googleOneTap->findOrCreateUser($googleUser);

        $this->assertEquals($existingUser->id, $user->id);
        $this->assertEquals('123456789', $user->google_id);
    }

    /** @test */
    public function it_can_find_existing_user_by_email()
    {
        // Create existing user without Google ID
        $existingUser = User::create([
            'name' => 'Existing User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        config(['google-onetap.security.update_existing_users' => true]);

        $googleUser = [
            'google_id' => '123456789',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/avatar.jpg',
            'email_verified' => true,
        ];

        $user = $this->googleOneTap->findOrCreateUser($googleUser);

        $this->assertEquals($existingUser->id, $user->id);
        $this->assertEquals('123456789', $user->google_id);
        $this->assertEquals('https://example.com/avatar.jpg', $user->avatar);
    }

    /** @test */
    public function it_throws_exception_when_user_creation_disabled()
    {
        config(['google-onetap.security.create_users' => false]);

        $googleUser = [
            'google_id' => '123456789',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/avatar.jpg',
            'email_verified' => true,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User creation is not allowed');

        $this->googleOneTap->findOrCreateUser($googleUser);
    }

    /** @test */
    public function it_does_not_update_existing_user_when_disabled()
    {
        // Create existing user without Google ID
        $existingUser = User::create([
            'name' => 'Existing User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        config(['google-onetap.security.update_existing_users' => false]);

        $googleUser = [
            'google_id' => '123456789',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/avatar.jpg',
            'email_verified' => true,
        ];

        $user = $this->googleOneTap->findOrCreateUser($googleUser);

        $this->assertEquals($existingUser->id, $user->id);
        $this->assertNull($user->google_id); // Should not be updated
        $this->assertNull($user->avatar); // Should not be updated
    }
}
