<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Outhebox\Translations\Concerns\DisplayHelper;

use function Laravel\Prompts\info;

class UpdateCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:update';

    protected $description = 'Re-publish assets and run migrations after a package update';

    public function handle(): int
    {
        $this->displayHeader('Update');

        $this->call('vendor:publish', [
            '--tag' => 'translations-assets',
            '--force' => true,
            '--no-interaction' => true,
        ]);

        if ($this->proIsInstalled()) {
            $this->call('vendor:publish', [
                '--tag' => 'translations-pro-assets',
                '--force' => true,
                '--no-interaction' => true,
            ]);
        }

        $this->call('migrate', ['--no-interaction' => true]);

        info('Translations updated successfully.');

        return self::SUCCESS;
    }

    private function proIsInstalled(): bool
    {
        return class_exists(\Outhebox\TranslationsPro\Providers\TranslationsProServiceProvider::class);
    }
}
