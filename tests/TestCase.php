<?php

namespace Sumaia\GoogleOneTapLogin\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sumaia\GoogleOneTapLogin\Providers\GoogleOneTapServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Only load test migrations, not package migrations to avoid conflicts
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            GoogleOneTapServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set up application key for encryption
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        config()->set('app.cipher', 'AES-256-CBC');

        // Set up Google One Tap configuration for testing
        config()->set('google-onetap.client_id', 'test-client-id');
        config()->set('google-onetap.client_secret', 'test-client-secret');
    }


}
