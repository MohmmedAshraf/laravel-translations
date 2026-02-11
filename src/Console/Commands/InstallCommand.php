<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Laravel\Prompts\Prompt;
use Outhebox\Translations\Concerns\DisplayHelper;
use Outhebox\Translations\Database\Seeders\LanguageSeeder;
use Outhebox\Translations\Enums\AuthDriver;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class InstallCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:install
        {--import : Also import translations after setup}';

    protected $description = 'Install the translations module (migrate, seed languages, create first contributor)';

    public function handle(): int
    {
        $this->displayHeader('Install');

        if ($this->isV1Schema()) {
            return $this->handleV1Detected();
        }

        $interactive = $this->input->isInteractive();

        if (! $interactive || confirm('Publish configuration file?', default: true)) {
            $this->publishConfig();
        }

        if (! $interactive || confirm('Publish frontend assets?', default: true)) {
            $this->publishAssets();
        }

        if (! $interactive || confirm('Run database migrations?', default: true)) {
            $this->callSubCommand('migrate', ['--no-interaction' => true]);
        }

        if (! $interactive || confirm('Seed default languages?', default: true)) {
            $this->callSubCommand('db:seed', ['--class' => LanguageSeeder::class, '--no-interaction' => true]);
        }

        if (! File::isDirectory(lang_path())) {
            if (! $interactive || confirm('Lang folder not found. Publish default language files?', default: true)) {
                $this->callSubCommand('lang:publish', ['--no-interaction' => true]);
            }
        }

        if (config('translations.auth.driver') === AuthDriver::Contributors->value) {
            if (! $interactive || confirm('Create first contributor?', default: true)) {
                $this->createFirstContributor();
            }
        }

        if ($this->option('import')) {
            if (! $interactive || confirm('Import existing translation files?', default: true)) {
                $this->callSubCommand('translations:import', ['--no-interaction' => true]);
            }
        }

        info('Translations installed successfully!');

        return self::SUCCESS;
    }

    private function isV1Schema(): bool
    {
        $connection = config('translations.database_connection');

        return Schema::connection($connection)->hasTable('ltu_phrases')
            && Schema::connection($connection)->hasTable('ltu_translation_files')
            && ! Schema::connection($connection)->hasTable('ltu_translation_keys');
    }

    private function handleV1Detected(): int
    {
        warning('v1 schema detected — you need to upgrade before installing v2.');

        if (! $this->input->isInteractive()) {
            $this->callSubCommand('translations:upgrade', ['--force' => true]);

            return self::SUCCESS;
        }

        if (confirm('Run translations:upgrade now?', default: true)) {
            $this->callSubCommand('translations:upgrade');

            return self::SUCCESS;
        }

        warning('Upgrade cancelled. Please run translations:upgrade manually before installing.');

        return self::FAILURE;
    }

    private function publishConfig(): void
    {
        $this->callSubCommand('vendor:publish', [
            '--tag' => 'translations-config',
            '--no-interaction' => true,
        ]);

        if ($this->proIsInstalled()) {
            $this->callSubCommand('vendor:publish', [
                '--tag' => 'translations-pro-config',
                '--no-interaction' => true,
            ]);
        }
    }

    private function publishAssets(): void
    {
        $this->callSubCommand('vendor:publish', [
            '--tag' => 'translations-assets',
            '--force' => true,
            '--no-interaction' => true,
        ]);

        if ($this->proIsInstalled()) {
            $this->callSubCommand('vendor:publish', [
                '--tag' => 'translations-pro-assets',
                '--force' => true,
                '--no-interaction' => true,
            ]);
        }
    }

    private function callSubCommand(string $command, array $arguments = []): int
    {
        $result = $this->call($command, $arguments);
        Prompt::interactive($this->input->isInteractive());

        return $result;
    }

    private function proIsInstalled(): bool
    {
        return class_exists(\Outhebox\TranslationsPro\Providers\TranslationsProServiceProvider::class);
    }

    private function createFirstContributor(): void
    {
        if (Contributor::query()->exists()) {
            info('Contributors already exist, skipping creation.');

            return;
        }

        if ($this->option('no-interaction')) {
            Contributor::query()->create([
                'name' => 'Admin',
                'email' => 'admin@translations.local',
                'password' => 'password',
                'role' => ContributorRole::Owner,
            ]);

            info('Created default contributor: admin@translations.local / password');

            return;
        }

        $name = text(
            label: 'What is the contributor\'s name?',
            required: true,
        );

        $email = text(
            label: 'What is the contributor\'s email?',
            required: true,
            validate: function (string $value): ?string {
                if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return 'Please enter a valid email address.';
                }

                return null;
            },
        );

        $contributorPassword = password(
            label: 'What is the contributor\'s password?',
            required: true,
        );

        Contributor::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $contributorPassword,
            'role' => ContributorRole::Owner,
        ]);

        info("Created contributor: {$email}");
    }
}
