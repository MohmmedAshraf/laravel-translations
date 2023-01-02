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
    protected $signature = 'translations:import {--F|fresh : Truncate all translations and phrases before importing}';

    protected $description = 'Sync translation all keys from the translation files to the database';

    public function __construct(public  TranslationsManager $manager)
    {
        parent::__construct();
    }

    protected function truncateTables()
    {
        Schema::disableForeignKeyConstraints();

        Phrase::truncate();
        Translation::truncate();
        TranslationFile::truncate();

        Schema::enableForeignKeyConstraints();
    }

    public function handle()
    {
        $this->importLanguages();

        if ($this->option('fresh') && $this->confirm('Are you sure you want to truncate all translations and phrases?')) {
            $this->info('Truncating translations and phrases...'.PHP_EOL);

            $this->truncateTables();
        }

        $translation = $this->createOrGetSourceLanguage();

        $this->withProgressBar($this->manager->getLocales(), function ($locale) use ($translation) {
            $this->syncTranslations($translation, $locale);
        });
    }

    public function createOrGetSourceLanguage(): Translation
    {
        $language = Language::where('code', config('translations.source_language'))->first();

        if (! $language) {
            $this->error('Language with code '.config('translations.source_language').' not found');

            exit;
        }

        $translation = Translation::firstOrCreate([
            'language_id' => $language->id,
            'source' => true,
        ]);

        $this->syncTranslations($translation, $language->code);

        return $translation;
    }

    public function syncTranslations(Translation $translation, string $locale)
    {
        foreach ($this->manager->getTranslations($locale) as $file => $translations) {
            foreach (Arr::dot($translations) as $key => $value) {
                $this->syncPhrases($translation, $key, $value, $locale, $file);
            }
        }
    }

    public function syncPhrases(Translation $source, $key, $value, $locale, $file)
    {
        if (is_array($value) && empty($value)) {
            return;
        }

        $language = Language::where('code', $locale)->first();

        if (! $language) {
            $this->error("Language with code $locale not found");

            return;
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

    protected function importLanguages()
    {
        if (! Schema::hasTable('ltu_languages')) {
            $this->error('The ltu_languages table is not present in the database. Please run the migrations and try again.');

            return;
        }

        $languages = [
            [
                'code' => 'af',
                'name' => 'Afrikaans',
                'rtl' => false,
            ],
            [
                'code' => 'sq',
                'name' => 'Albanian',
                'rtl' => false,
            ],
            [
                'code' => 'am',
                'name' => 'Amharic',
                'rtl' => false,
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'rtl' => true,
            ],
            [
                'code' => 'hy',
                'name' => 'Armenian',
                'rtl' => false,
            ],
            [
                'code' => 'as',
                'name' => 'Assamese',
                'rtl' => false,
            ],
            [
                'code' => 'ay',
                'name' => 'Aymara',
                'rtl' => false,
            ],
            [
                'code' => 'az',
                'name' => 'Azerbaijani',
                'rtl' => false,
            ],
            [
                'code' => 'bm',
                'name' => 'Bambara',
                'rtl' => false,
            ],
            [
                'code' => 'eu',
                'name' => 'Basque',
                'rtl' => false,
            ],
            [
                'code' => 'be',
                'name' => 'Belarusian',
                'rtl' => false,
            ],
            [
                'code' => 'bn',
                'name' => 'Bengali',
                'rtl' => false,
            ],
            [
                'code' => 'bho',
                'name' => 'Bhojpuri',
                'rtl' => false,
            ],
            [
                'code' => 'bs',
                'name' => 'Bosnian',
                'rtl' => false,
            ],
            [
                'code' => 'bg',
                'name' => 'Bulgarian',
                'rtl' => false,
            ],
            [
                'code' => 'ca',
                'name' => 'Catalan',
                'rtl' => false,
            ],
            [
                'code' => 'ceb',
                'name' => 'Cebuano',
                'rtl' => false,
            ],
            [
                'code' => 'zh',
                'name' => 'Chinese',
                'rtl' => false,
            ],
            [
                'code' => 'zh-TW',
                'name' => 'Chinese (Traditional)',
                'rtl' => false,
            ],
            [
                'code' => 'co',
                'name' => 'Corsican',
                'rtl' => false,
            ],
            [
                'code' => 'hr',
                'name' => 'Croatian',
                'rtl' => false,
            ],
            [
                'code' => 'cs',
                'name' => 'Czech',
                'rtl' => false,
            ],
            [
                'code' => 'da',
                'name' => 'Danish',
                'rtl' => false,
            ],
            [
                'code' => 'dv',
                'name' => 'Divehi',
                'rtl' => false,
            ],
            [
                'code' => 'nl',
                'name' => 'Dutch',
                'rtl' => false,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'rtl' => false,
            ],
            [
                'code' => 'et',
                'name' => 'Estonian',
                'rtl' => false,
            ],
            [
                'code' => 'fil',
                'name' => 'Filipino',
                'rtl' => false,
            ],
            [
                'code' => 'fi',
                'name' => 'Finnish',
                'rtl' => false,
            ],
            [
                'code' => 'fr',
                'name' => 'French',
                'rtl' => false,
            ],
            [
                'code' => 'gl',
                'name' => 'Galician',
                'rtl' => false,
            ],
            [
                'code' => 'ka',
                'name' => 'Georgian',
                'rtl' => false,
            ],
            [
                'code' => 'de',
                'name' => 'German',
                'rtl' => false,
            ],
            [
                'code' => 'el',
                'name' => 'Greek',
                'rtl' => false,
            ],
            [
                'code' => 'gn',
                'name' => 'Guarani',
                'rtl' => false,
            ],
            [
                'code' => 'gu',
                'name' => 'Gujarati',
                'rtl' => false,
            ],
            [
                'code' => 'ht',
                'name' => 'Haitian Creole',
                'rtl' => false,
            ],
            [
                'code' => 'ha',
                'name' => 'Hausa',
                'rtl' => false,
            ],
            [
                'code' => 'haw',
                'name' => 'Hawaiian',
                'rtl' => false,
            ],
            [
                'code' => 'he',
                'name' => 'Hebrew',
                'rtl' => true,
            ],
            [
                'code' => 'hi',
                'name' => 'Hindi',
                'rtl' => false,
            ],
            [
                'code' => 'hu',
                'name' => 'Hungarian',
                'rtl' => false,
            ],
            [
                'code' => 'is',
                'name' => 'Icelandic',
                'rtl' => false,
            ],
            [
                'code' => 'ig',
                'name' => 'Igbo',
                'rtl' => false,
            ],
            [
                'code' => 'id',
                'name' => 'Indonesian',
                'rtl' => false,
            ],
            [
                'code' => 'ga',
                'name' => 'Irish',
                'rtl' => false,
            ],
            [
                'code' => 'it',
                'name' => 'Italian',
                'rtl' => false,
            ],
            [
                'code' => 'ja',
                'name' => 'Japanese',
                'rtl' => false,
            ],
            [
                'code' => 'jv',
                'name' => 'Javanese',
                'rtl' => false,
            ],
            [
                'code' => 'kn',
                'name' => 'Kannada',
                'rtl' => false,
            ],
            [
                'code' => 'kk',
                'name' => 'Kazakh',
                'rtl' => false,
            ],
            [
                'code' => 'km',
                'name' => 'Khmer',
                'rtl' => false,
            ],
            [
                'code' => 'rw',
                'name' => 'Kinyarwanda',
                'rtl' => false,
            ],
            [
                'code' => 'ko',
                'name' => 'Korean',
                'rtl' => false,
            ],
            [
                'code' => 'ku',
                'name' => 'Kurdish',
                'rtl' => false,
            ],
            [
                'code' => 'ky',
                'name' => 'Kyrgyz',
                'rtl' => false,
            ],
            [
                'code' => 'lo',
                'name' => 'Lao',
                'rtl' => false,
            ],
            [
                'code' => 'la',
                'name' => 'Latin',
                'rtl' => false,
            ],
            [
                'code' => 'lv',
                'name' => 'Latvian',
                'rtl' => false,
            ],
            [
                'code' => 'ln',
                'name' => 'Lingala',
                'rtl' => false,
            ],
            [
                'code' => 'lt',
                'name' => 'Lithuanian',
                'rtl' => false,
            ],
            [
                'code' => 'mk',
                'name' => 'Macedonian',
                'rtl' => false,
            ],
            [
                'code' => 'ms',
                'name' => 'Malay',
                'rtl' => false,
            ],
            [
                'code' => 'ml',
                'name' => 'Malayalam',
                'rtl' => false,
            ],
            [
                'code' => 'mt',
                'name' => 'Maltese',
                'rtl' => false,
            ],
            [
                'code' => 'mi',
                'name' => 'Maori',
                'rtl' => false,
            ],
            [
                'code' => 'mr',
                'name' => 'Marathi',
                'rtl' => false,
            ],
            [
                'code' => 'mn',
                'name' => 'Mongolian',
                'rtl' => false,
            ],
            [
                'code' => 'ne',
                'name' => 'Nepali',
                'rtl' => false,
            ],
            [
                'code' => 'no',
                'name' => 'Norwegian',
                'rtl' => false,
            ],
            [
                'code' => 'ps',
                'name' => 'Pashto',
                'rtl' => false,
            ],
            [
                'code' => 'fa',
                'name' => 'Persian',
                'rtl' => true,
            ],
            [
                'code' => 'pl',
                'name' => 'Polish',
                'rtl' => false,
            ],
            [
                'code' => 'pt',
                'name' => 'Portuguese',
                'rtl' => false,
            ],
            [
                'code' => 'pa',
                'name' => 'Punjabi',
                'rtl' => false,
            ],
            [
                'code' => 'ro',
                'name' => 'Romanian',
                'rtl' => false,
            ],
            [
                'code' => 'ru',
                'name' => 'Russian',
                'rtl' => false,
            ],
            [
                'code' => 'sm',
                'name' => 'Samoan',
                'rtl' => false,
            ],
            [
                'code' => 'sr',
                'name' => 'Serbian',
                'rtl' => false,
            ],
            [
                'code' => 'st',
                'name' => 'Sesotho',
                'rtl' => false,
            ],
            [
                'code' => 'sn',
                'name' => 'Shona',
                'rtl' => false,
            ],
            [
                'code' => 'sd',
                'name' => 'Sindhi',
                'rtl' => false,
            ],
            [
                'code' => 'si',
                'name' => 'Sinhala',
                'rtl' => false,
            ],
            [
                'code' => 'sk',
                'name' => 'Slovak',
                'rtl' => false,
            ],
            [
                'code' => 'sl',
                'name' => 'Slovenian',
                'rtl' => false,
            ],
            [
                'code' => 'so',
                'name' => 'Somali',
                'rtl' => false,
            ],
            [
                'code' => 'es',
                'name' => 'Spanish',
                'rtl' => false,
            ],
            [
                'code' => 'su',
                'name' => 'Sundanese',
                'rtl' => false,
            ],
            [
                'code' => 'sw',
                'name' => 'Swahili',
                'rtl' => false,
            ],
            [
                'code' => 'sv',
                'name' => 'Swedish',
                'rtl' => false,
            ],
            [
                'code' => 'tg',
                'name' => 'Tajik',
                'rtl' => false,
            ],
            [
                'code' => 'ta',
                'name' => 'Tamil',
                'rtl' => false,
            ],
            [
                'code' => 'te',
                'name' => 'Telugu',
                'rtl' => false,
            ],
            [
                'code' => 'th',
                'name' => 'Thai',
                'rtl' => false,
            ],
            [
                'code' => 'tr',
                'name' => 'Turkish',
                'rtl' => false,
            ],
            [
                'code' => 'uk',
                'name' => 'Ukrainian',
                'rtl' => false,
            ],
            [
                'code' => 'ur',
                'name' => 'Urdu',
                'rtl' => false,
            ],
            [
                'code' => 'uz',
                'name' => 'Uzbek',
                'rtl' => false,
            ],
            [
                'code' => 'vi',
                'name' => 'Vietnamese',
                'rtl' => false,
            ],
            [
                'code' => 'cy',
                'name' => 'Welsh',
                'rtl' => false,
            ],
            [
                'code' => 'xh',
                'name' => 'Xhosa',
                'rtl' => false,
            ],
            [
                'code' => 'yi',
                'name' => 'Yiddish',
                'rtl' => false,
            ],
            [
                'code' => 'yo',
                'name' => 'Yoruba',
                'rtl' => false,
            ],
            [
                'code' => 'zu',
                'name' => 'Zulu',
                'rtl' => false,
            ],
        ];

        $DBLanguages = Language::all();
        $languages = collect($languages)->reject(function ($language) use ($DBLanguages) {
            return $DBLanguages->contains('code', $language['code']);
        })->toArray();
        Language::insert($languages);
    }
}
