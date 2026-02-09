<?php

namespace Outhebox\Translations\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Outhebox\Translations\Providers\TranslationsServiceProvider;
use Spatie\QueryBuilder\QueryBuilderServiceProvider;

abstract class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            if (str_starts_with($modelName, 'Workbench\\')) {
                return 'Workbench\\Database\\Factories\\'.class_basename($modelName).'Factory';
            }

            return 'Outhebox\\Translations\\Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../vendor/orchestra/testbench-core/laravel/migrations');
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        config()->set('inertia.testing.ensure_pages_exist', false);
        config()->set('inertia.ssr.enabled', false);
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
        config()->set('cache.default', 'array');
    }

    protected function getPackageProviders($app): array
    {
        return [
            InertiaServiceProvider::class,
            QueryBuilderServiceProvider::class,
            TranslationsServiceProvider::class,
        ];
    }
}
