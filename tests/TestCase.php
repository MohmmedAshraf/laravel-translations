<?php

namespace Outhebox\TranslationsUI\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Orchestra\Testbench\TestCase as Orchestra;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\TranslationsUIServiceProvider;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected Contributor $owner;

    protected Contributor $translator;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::clear();

        Gate::define('viewTranslationsUI', fn () => true);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Outhebox\\TranslationsUI\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->owner = Contributor::factory([
            'role' => RoleEnum::owner,
        ])->create();

        $this->translator = Contributor::factory([
            'role' => RoleEnum::translator,
        ])->create();
    }

    protected function refreshTestDatabase(): void
    {
        if (! RefreshDatabaseState::$migrated) {
            //$this->artisan('vendor:publish', ['--tag' => 'mixpost-migrations', '--force' => true])->run();
            //$this->artisan('migrate:fresh', $this->migrateFreshUsing());

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('inertia.testing.ensure_pages_exist', false);

        $migration = include __DIR__.'/../database/migrations/create_languages_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_translations_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_translation_files_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_phrases_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_contributors_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_invites_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/add_is_root_to_translation_files_table.php';
        $migration->up();
    }

    protected function getPackageProviders($app): array
    {
        return [
            TranslationsUIServiceProvider::class,
            RouteServiceProvider::class,
        ];
    }
}
