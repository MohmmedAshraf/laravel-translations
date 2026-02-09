<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Outhebox\Translations\Concerns\DisplayHelper;
use Outhebox\Translations\Services\Importer\TranslationImporter;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class ImportTranslationsCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:import
        {--fresh : Delete all existing translations before importing}
        {--no-overwrite : Do not overwrite existing translations}';

    protected $description = 'Import translation files from disk into the database';

    public function handle(TranslationImporter $importer): int
    {
        $this->displayHeader('Import');

        $fresh = (bool) $this->option('fresh');
        $overwrite = ! $this->option('no-overwrite');

        if ($fresh && ! $this->option('no-interaction')) {
            warning('This will delete ALL existing translations and re-import from disk.');

            if (! confirm('Are you sure you want to continue?', true)) {
                info('Import cancelled.');

                return self::SUCCESS;
            }
        }

        info('Importing translations...');

        $result = $importer->import([
            'fresh' => $fresh,
            'overwrite' => $overwrite,
            'source' => 'cli',
        ]);

        $durationSec = round($result->durationMs / 1000, 2);

        info("Import completed in {$durationSec}s");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Locales', $result->localeCount],
                ['Total keys', $result->keyCount],
                ['New keys', $result->newCount],
                ['Updated', $result->updatedCount],
            ]
        );

        return self::SUCCESS;
    }
}
