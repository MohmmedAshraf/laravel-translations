<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Outhebox\Translations\Concerns\DisplayHelper;
use Outhebox\Translations\Services\Exporter\TranslationExporter;

use function Laravel\Prompts\info;

class ExportTranslationsCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:export
        {--locale= : Export only a specific locale}
        {--group= : Export only a specific group}';

    protected $description = 'Export translations from the database to language files';

    public function handle(TranslationExporter $exporter): int
    {
        $this->displayHeader('Export');

        info('Exporting translations...');

        $result = $exporter->export([
            'locale' => $this->option('locale'),
            'group' => $this->option('group'),
            'source' => 'cli',
        ]);

        $durationSec = round($result->durationMs / 1000, 2);

        info("Export completed in {$durationSec}s");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Locales', $result->localeCount],
                ['Files written', $result->fileCount],
                ['Keys exported', $result->keyCount],
            ]
        );

        return self::SUCCESS;
    }
}
