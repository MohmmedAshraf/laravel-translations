<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Outhebox\Translations\Concerns\DisplayHelper;

use function Laravel\Prompts\info;

class UpdateCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:update';

    protected $description = 'Re-publish the translations UI assets after a package update';

    public function handle(): int
    {
        $this->displayHeader('Update');

        $this->call('vendor:publish', [
            '--tag' => 'translations-assets',
            '--force' => true,
        ]);

        info('Translations assets updated.');

        return self::SUCCESS;
    }
}
