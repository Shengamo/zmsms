<?php

namespace Shengamo\Zmsms\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Shengamo\Zmsms\ZmsmsServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load the configuration for the package
        $this->app['config']->set('zmsms.base_url', 'https://zmsms.online/api/v1/');
        $this->app['config']->set('zmsms.username', 'test_username');
        $this->app['config']->set('zmsms.password', 'test_password');
    }

    protected function getPackageProviders($app): array
    {
        return [
            ZmsmsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Perform additional environment setup if needed
    }
}
