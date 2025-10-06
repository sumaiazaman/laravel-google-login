<?php

namespace Sumaia\GoogleOneTapLogin\Tests\Unit;

use Sumaia\GoogleOneTapLogin\Tests\TestCase;

class ConfigurationTest extends TestCase
{
    public function test_default_configuration_values()
    {
        $this->assertEquals('/login', config('google-onetap.routes.login'));
        $this->assertEquals('/auth/google/callback', config('google-onetap.routes.callback'));
        $this->assertEquals('/dashboard', config('google-onetap.routes.dashboard'));
        $this->assertEquals('/logout', config('google-onetap.routes.logout'));
    }

    public function test_route_names_configuration()
    {
        $this->assertEquals('google-onetap.login', config('google-onetap.route_names.login'));
        $this->assertEquals('google-onetap.callback', config('google-onetap.route_names.callback'));
        $this->assertEquals('google-onetap.dashboard', config('google-onetap.route_names.dashboard'));
        $this->assertEquals('google-onetap.logout', config('google-onetap.route_names.logout'));
    }

    public function test_middleware_configuration()
    {
        $this->assertEquals(['web'], config('google-onetap.middleware.web'));
        $this->assertEquals(['web', 'auth'], config('google-onetap.middleware.auth'));
        $this->assertEquals(['web', 'guest'], config('google-onetap.middleware.guest'));
    }

    public function test_user_model_configuration()
    {
        $this->assertEquals('App\Models\User', config('google-onetap.user.model'));
        $this->assertEquals('google_id', config('google-onetap.user.fields.google_id'));
        $this->assertEquals('name', config('google-onetap.user.fields.name'));
        $this->assertEquals('email', config('google-onetap.user.fields.email'));
        $this->assertEquals('avatar', config('google-onetap.user.fields.avatar'));
    }

    public function test_security_configuration()
    {
        $this->assertTrue(config('google-onetap.security.verify_email'));
        $this->assertTrue(config('google-onetap.security.auto_verify_email'));
        $this->assertTrue(config('google-onetap.security.create_users'));
        $this->assertTrue(config('google-onetap.security.update_existing_users'));
    }

    public function test_one_tap_ui_configuration()
    {
        $this->assertFalse(config('google-onetap.one_tap.auto_prompt'));
        $this->assertEquals('signin', config('google-onetap.one_tap.context'));
        $this->assertEquals('popup', config('google-onetap.one_tap.ux_mode'));
        
        $buttonConfig = config('google-onetap.one_tap.button');
        $this->assertEquals('standard', $buttonConfig['type']);
        $this->assertEquals('rectangular', $buttonConfig['shape']);
        $this->assertEquals('outline', $buttonConfig['theme']);
        $this->assertEquals('signin_with', $buttonConfig['text']);
        $this->assertEquals('large', $buttonConfig['size']);
        $this->assertEquals('left', $buttonConfig['logo_alignment']);
    }

    public function test_redirect_configuration()
    {
        $this->assertEquals('/dashboard', config('google-onetap.redirects.after_login'));
        $this->assertEquals('/login', config('google-onetap.redirects.after_logout'));
    }
}
