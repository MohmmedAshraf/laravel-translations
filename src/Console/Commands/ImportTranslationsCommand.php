<?php

namespace Outhebox\LaravelTranslations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use Outhebox\LaravelTranslations\Models\TranslationFile;
use Outhebox\LaravelTranslations\TranslationsManager;

class ImportTranslationsCommand extends Command
{
    public TranslationsManager $manager;

    protected $signature = 'translations:import {--F|fresh : Truncate all translations and phrases before importing}';

    protected $description = 'Sync translation all keys from the translation files to the database';

    public function __construct(TranslationsManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    protected function truncateTables(): void
    {
        Schema::withoutForeignKeyConstraints(function () {
            Phrase::truncate();
            Translation::truncate();
            TranslationFile::truncate();
        });
    }

    public function handle(): void
    {
        $this->importLanguages();

        if ($this->option('fresh') && $this->confirm('Are you sure you want to truncate all translations and phrases?')) {
            $this->info('Truncating translations and phrases...'.PHP_EOL);

            $this->truncateTables();
        }

        $translation = $this->createOrGetSourceLanguage();

        $this->info('Importing translations...'.PHP_EOL);

        $this->withProgressBar($this->manager->getLocales(), function ($locale) use ($translation) {
            $this->syncTranslations($translation, $locale);
        });
    }

    public function createOrGetSourceLanguage(): Translation
    {
        $language = Language::where('code', config('translations.source_language'))->first();

        if (! $language) {
            $this->error('Language with code '.config('translations.source_language').' not found'.PHP_EOL);

            exit;
        }

        if (! is_dir(lang_path()) || count(scandir(lang_path())) <= 2) {
            if ($this->confirm('It seems that you don\'t have any languages yet, would you like to publish the default language files?', true)) {
                $this->call('lang:publish');
            } else {
                $this->error('We can\'t find any languages in your project, please run the lang:publish command first.');

                exit;
            }
        }

        $translation = Translation::firstOrCreate([
            'source' => true,
            'language_id' => $language->id,
        ]);

        $this->syncTranslations($translation, $language->code);

        return $translation;
    }

    public function syncTranslations(Translation $translation, string $locale): void
    {
        foreach ($this->manager->getTranslations($locale) as $file => $translations) {
            foreach (Arr::dot($translations) as $key => $value) {
                $this->syncPhrases($translation, $key, $value, $locale, $file);
            }
        }
    }

    public function syncPhrases(Translation $source, $key, $value, $locale, $file): void
    {
        if (is_array($value) && empty($value)) {
            return;
        }

        $language = Language::where('code', $locale)->first();

        if (! $language) {
            $this->error(PHP_EOL."Language with code $locale not found");

            exit;
        }

        $translation = Translation::firstOrCreate([
            'language_id' => $language->id,
            'source' => config('translations.source_language') === $locale,
        ]);

        $translationFile = TranslationFile::firstOrCreate([
            'name' => pathinfo($file, PATHINFO_FILENAME),
            'extension' => pathinfo($file, PATHINFO_EXTENSION),
        ]);

        $translation->phrases()->updateOrCreate([
            'key' => $key,
            'group' => $translationFile->name,
            'translation_file_id' => $translationFile->id,
        ], [
            'value' => $value,
            'parameters' => $this->manager->getPhraseParameters($value),
            'phrase_id' => $translation->source ? null : $source->phrases()->where('key', $key)->first()?->id,
        ]);
    }

    protected function importLanguages(): void
    {
        if (! Schema::hasTable('ltu_languages') || Language::count() === 0) {
            if ($this->confirm('The ltu_languages table does not exist or is empty, would you like to install the default languages?', true)) {
                $this->call('translations:install');
            } else {
                $this->error('The ltu_languages table does not exist or is empty, please run the translations:install command first.');

                exit;
            }
        }
    }
}
