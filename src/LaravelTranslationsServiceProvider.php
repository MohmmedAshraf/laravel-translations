<?php

namespace Outhebox\LaravelTranslations;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Gate;
use Outhebox\LaravelTranslations\Console\Commands\ContributorCommand;
use Outhebox\LaravelTranslations\Console\Commands\ExportTranslationsCommand;
use Outhebox\LaravelTranslations\Console\Commands\ImportTranslationsCommand;
use Outhebox\LaravelTranslations\Console\Commands\PublishAssetsCommand;
use Outhebox\LaravelTranslations\Exceptions\TranslationsUIExceptionHandler;
use Outhebox\LaravelTranslations\Models\Contributor;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTranslationsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-translations')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasMigrations([
                'create_languages_table',
                'create_translations_table',
                'create_translation_files_table',
                'create_phrases_table',
                'create_contributors_table',
                'create_contributor_languages_table',
            ])
            ->hasCommands([
                ContributorCommand::class,
                PublishAssetsCommand::class,
                ImportTranslationsCommand::class,
                ExportTranslationsCommand::class,
            ])->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $this->writeSeparationLine($command);
                        $command->line('Laravel Translations UI installation, Simple and friendly user interface for managing translations in a Laravel app.');
                        $command->line('Laravel version: '.app()->version());
                        $command->line('PHP version: '.trim(phpversion()));
                        $command->line(' ');
                        $command->line('Github: https://github.com/MohmmedAshraf/laravel-translations');
                        $this->writeSeparationLine($command);
                        $command->line('');

                        $command->comment('Publishing assets');
                        $command->call('translations:publish-assets');
                    })
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('MohmmedAshraf/laravel-translations')
                    ->endWith(function (InstallCommand $command) {
                        $appUrl = config('app.url');

                        $command->line("Visit the Laravel Translations UI at $appUrl/translations");
                    });
            });
    }

    public function packageBooted(): void
    {
        $this->registerAuthDriver();

        $this->registerExceptionHandler();

        Gate::define('viewTranslationsUI', function () {
            return true;
        });
    }

    private function registerAuthDriver(): void
    {
        $this->app->config->set('auth.providers.ltu_contributors', [
            'driver' => 'eloquent',
            'model' => Contributor::class,
        ]);

        $this->app->config->set('auth.guards.translations', [
            'driver' => 'session',
            'provider' => 'ltu_contributors',
        ]);
    }

    protected function registerExceptionHandler(): void
    {
        app()->bind(ExceptionHandler::class, TranslationsUIExceptionHandler::class);
    }

    protected function writeSeparationLine(InstallCommand $command): void
    {
        $command->info('*---------------------------------------------------------------------------*');
    }
}
