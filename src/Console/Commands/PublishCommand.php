<?php

namespace Outhebox\TranslationsUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishCommand extends Command
{
    public $signature = 'translations:publish {--force : Overwrite any existing files}';

    public $description = 'Publish all of the Translations UI resources';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        if (! $force && File::exists(public_path('vendor/translations-ui'))) {
            $this->line('Your application already have the Translations UI assets');

            if (! $this->confirm('Do you want to rewrite?')) {
                return self::FAILURE;
            }
        }

        File::deleteDirectory(public_path('vendor/translations-ui'));
        File::copyDirectory(__DIR__.'/../../../resources/dist/vendor', public_path('vendor'));
        File::copy(__DIR__.'/../../../resources/favicon.ico', public_path('vendor/translations-ui/favicon.ico'));

        $this->info('Assets was published to [public/vendor/translations-ui]');

        return self::SUCCESS;
    }
}
