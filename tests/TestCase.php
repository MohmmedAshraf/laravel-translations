<?php

namespace Outhebox\LaravelTranslations\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Outhebox\LaravelTranslations\LaravelTranslationsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Outhebox\\LaravelTranslations\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            LaravelTranslationsServiceProvider::class,
            RouteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        Schema::dropAllTables();

        $migration = include __DIR__.'/../database/migrations/2018_08_08_100000_create_translations_tables.php';
        $migration->up();
    }
}
