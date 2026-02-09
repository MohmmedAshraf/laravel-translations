<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Outhebox\Translations\Concerns\DisplayHelper;
use Outhebox\Translations\Database\Seeders\LanguageSeeder;
use Outhebox\Translations\Enums\AuthDriver;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:install
        {--import : Also import translations after setup}';

    protected $description = 'Install the translations module (migrate, seed languages, create first contributor)';

    public function handle(): int
    {
        $this->displayHeader('Install');

        $this->call('migrate', ['--no-interaction' => true]);

        $this->call('db:seed', ['--class' => LanguageSeeder::class, '--no-interaction' => true]);

        if (config('translations.auth.driver') === AuthDriver::Contributors->value) {
            $this->createFirstContributor();
        }

        if ($this->option('import')) {
            $this->call('translations:import', ['--no-interaction' => true]);
        }

        info('Translations installed successfully!');

        return self::SUCCESS;
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
            default: 'Admin',
            required: true,
        );

        $email = text(
            label: 'What is the contributor\'s email?',
            default: 'admin@translations.local',
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
