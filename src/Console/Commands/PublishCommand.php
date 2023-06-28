<?php

namespace Outhebox\LaravelTranslations\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    public $signature = 'translations:publish {--force : Overwrite any existing files}';

    public $description = 'Publish all of the Translations UI resources';

    public function handle(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'translations-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'translations-assets',
            '--force' => true,
        ]);
    }
}
