<?php

namespace Ajaycalicut17\LaravelTrash\Tests;

use Ajaycalicut17\LaravelTrash\LaravelTrashServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMockingConsoleOutput();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTrashServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
