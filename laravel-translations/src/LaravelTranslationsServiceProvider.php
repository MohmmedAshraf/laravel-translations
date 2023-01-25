<?php

namespace Outhebox\LaravelTranslations;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Outhebox\LaravelTranslations\Console\Commands;
use Outhebox\LaravelTranslations\Http\Livewire\Modals\CreateSourceKey;
use Outhebox\LaravelTranslations\Http\Livewire\Modals\CreateTranslation;
use Outhebox\LaravelTranslations\Http\Livewire\PhraseForm;
use Outhebox\LaravelTranslations\Http\Livewire\PhraseList;
use Outhebox\LaravelTranslations\Http\Livewire\SourcePhrase;
use Outhebox\LaravelTranslations\Http\Livewire\TranslationsList;
use Outhebox\LaravelTranslations\Http\Livewire\Widgets\ExportTranslations;

class LaravelTranslationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerMigrations();
        $this->registerLivewireComponents();
    }

    protected function registerRoutes()
    {
        Route::group([
            'domain' => config('translations.domain', null),
            'prefix' => config('translations.path'),
            'namespace' => 'Outhebox\LaravelTranslations\Http\Controllers',
            'middleware' => config('translations.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'translations');
    }

    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'translations-migrations');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/translations'),
            ], ['translations-assets', 'laravel-assets']);

            $this->publishes([
                __DIR__.'/../stubs/TranslationsServiceProvider.stub' => app_path('Providers/TranslationsServiceProvider.php'),
            ], 'translations-provider');

            $this->publishes([
                __DIR__.'/../config/translations.php' => config_path('translations.php'),
            ], 'translations-config');
        }
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
                Commands\PublishCommand::class,
                Commands\ImportTranslationsCommand::class,
                Commands\ExportTranslationsCommand::class,
            ]);
        }
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('translations-ui::phrase-list', PhraseList::class);
        Livewire::component('translations-ui::phrase-form', PhraseForm::class);
        Livewire::component('translations-ui::source-phrase', SourcePhrase::class);
        Livewire::component('translations-ui::translations-list', TranslationsList::class);
        Livewire::component('translations-ui::export-translations', ExportTranslations::class);
        Livewire::component('translations-ui::create-source-key-modal', CreateSourceKey::class);
        Livewire::component('translations-ui::create-translation-modal', CreateTranslation::class);
    }
}
