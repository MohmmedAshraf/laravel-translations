<?php

namespace Outhebox\TranslationsUI;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Outhebox\TranslationsUI\Console\Commands\CleanOldVersionCommand;
use Outhebox\TranslationsUI\Console\Commands\ContributorCommand;
use Outhebox\TranslationsUI\Console\Commands\ExportTranslationsCommand;
use Outhebox\TranslationsUI\Console\Commands\ImportTranslationsCommand;
use Outhebox\TranslationsUI\Console\Commands\PublishCommand;
use Outhebox\TranslationsUI\Exceptions\TranslationsUIExceptionHandler;
use Outhebox\TranslationsUI\Models\Contributor;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TranslationsUIServiceProvider extends PackageServiceProvider
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
                'create_invites_table',
                'add_is_root_to_translation_files_table',
            ])
            ->hasCommands([
                PublishCommand::class,
                ContributorCommand::class,
                CleanOldVersionCommand::class,
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
                        $command->call('translations:publish');
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
