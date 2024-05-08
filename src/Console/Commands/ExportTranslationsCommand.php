<?php

namespace Outhebox\TranslationsUI\Console\Commands;

use Illuminate\Console\Command;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

class ExportTranslationsCommand extends Command
{
    protected $signature = 'translations:export';

    protected $description = 'Export all translations to the language directory';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Exporting translations...'.PHP_EOL);

        TranslationsUI::export();

        $this->info('Translations exported successfully!'.PHP_EOL);
    }
}
