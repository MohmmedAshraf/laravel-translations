<?php

namespace Outhebox\Translations\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TranslationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/translations.php', 'translations');
    }

    public function boot(): void
    {
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureMigrations();
        $this->configureViews();
        $this->configureCommands();
    }

    private function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../../config/translations.php' => config_path('translations.php'),
        ], 'translations-config');

        $this->publishes([
            __DIR__.'/../../dist' => public_path('vendor/translations'),
        ], 'translations-assets');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'translations-migrations');
    }

    private function configureRoutes(): void
    {
        // Will be wired in Phase 2
    }

    private function configureMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    private function configureViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'translations');
    }

    private function configureCommands(): void
    {
        // Will be wired in Phase 2
    }
}
