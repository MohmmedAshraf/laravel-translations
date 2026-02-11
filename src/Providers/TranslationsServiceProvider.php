<?php

namespace Outhebox\Translations\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Outhebox\Translations\Console\Commands\CreateUserCommand;
use Outhebox\Translations\Console\Commands\ExportCommand;
use Outhebox\Translations\Console\Commands\ImportCommand;
use Outhebox\Translations\Console\Commands\InstallCommand;
use Outhebox\Translations\Console\Commands\StatusCommand;
use Outhebox\Translations\Console\Commands\UpdateCommand;
use Outhebox\Translations\Console\Commands\UpgradeCommand;
use Outhebox\Translations\Enums\AuthDriver;
use Outhebox\Translations\Http\Middleware\ShareTranslationsData;
use Outhebox\Translations\Http\Middleware\TranslationsAuth;
use Outhebox\Translations\Http\Middleware\TranslationsInertia;
use Outhebox\Translations\Http\Middleware\TranslationsRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Services\TranslationAuth as TranslationAuthService;

class TranslationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/translations.php', 'translations');

        $this->app->singleton('translations.auth', function ($app) {
            return new TranslationAuthService($app);
        });

        $this->app->alias('translations.auth', TranslationAuthService::class);
    }

    public function boot(): void
    {
        $this->configureAuth();
        $this->configureMiddleware();
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureMigrations();
        $this->configureViews();
        $this->configureCommands();

        $this->app->afterResolving('events', function ($events): void {
            if (class_exists(\Laravel\Octane\Events\RequestTerminated::class)) {
                $events->listen(\Laravel\Octane\Events\RequestTerminated::class, fn () => Translation::resetStaticState());
            }
        });
    }

    protected function configureAuth(): void
    {
        if (config('translations.auth.driver') !== AuthDriver::Contributors->value) {
            return;
        }

        $auth = $this->app['config'];

        $auth->set('auth.guards.translations', [
            'driver' => 'session',
            'provider' => 'translations_contributors',
        ]);

        $auth->set('auth.providers.translations_contributors', [
            'driver' => 'eloquent',
            'model' => Contributor::class,
        ]);
    }

    protected function configureMiddleware(): void
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('translations.auth', TranslationsAuth::class);
        $router->aliasMiddleware('translations.role', TranslationsRole::class);
    }

    protected function configurePublishing(): void
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

    protected function configureRoutes(): void
    {
        $routeConfig = Route::middleware($this->routeMiddleware())
            ->prefix(config('translations.path', 'translations'));

        if ($domain = config('translations.domain')) {
            $routeConfig->domain($domain);
        }

        $routeConfig->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

            if (config('translations.auth.driver') === AuthDriver::Contributors->value) {
                $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
            }
        });
    }

    protected function routeMiddleware(): array
    {
        return [...config('translations.middleware', ['web']), TranslationsInertia::class, ShareTranslationsData::class];
    }

    protected function configureMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    protected function configureViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'translations');
    }

    protected function configureCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            CreateUserCommand::class,
            ImportCommand::class,
            ExportCommand::class,
            InstallCommand::class,
            StatusCommand::class,
            UpdateCommand::class,
            UpgradeCommand::class,
        ]);
    }
}
