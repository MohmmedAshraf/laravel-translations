<?php

use Brick\VarExporter\VarExporter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use Outhebox\LaravelTranslations\Models\TranslationFile;
use Outhebox\LaravelTranslations\Tests\FilesystemMock;
use Outhebox\LaravelTranslations\TranslationsManager;

it('returns the correct list of locales', function () {
    $filesystem = new FilesystemMock();

    $translationsManager = new TranslationsManager($filesystem);
    $locales = $translationsManager->getLocales();

    expect($locales)->toBe(['en']);
});

it('returns the correct translations for a given locale', function () {
    Config::set('translations.exclude_files', ['en']);
    $filesystem = new FilesystemMock();

    $translationsManager = new TranslationsManager($filesystem);
    $translations = $translationsManager->getTranslations('en');
    expect($translations)->toBe(['auth.php' => [], 'validation.json' => []]);

    $translations = $translationsManager->getTranslations('');
    expect($translations)->toBe(['auth.php' => [], 'validation.json' => []]);
});

test('export creates a new translation file with the correct content', function () {
    $filesystem = new FilesystemMock();

    $translation = Translation::factory([
        'source' => true,
        'language_id' => Language::factory([
            'code' => 'en',
            'name' => 'English',
        ]),
    ])->has(Phrase::factory()->state([
        'phrase_id' => null,
        'translation_file_id' => TranslationFile::factory([
            'name' => 'auth',
            'extension' => 'php',
        ]),
    ]))->create();

    $translationsManager = new TranslationsManager($filesystem);
    $translationsManager->export();

    $fileName = $translation->phrases[0]->file->name.'.'.$translation->phrases[0]->file->extension;
    $fileNameInDisk = File::allFiles(lang_path($translation->language->code))[0]->getFilename();

    expect($fileName)->toBe($fileNameInDisk)
        ->and(File::get(lang_path($translation->language->code.DIRECTORY_SEPARATOR.$fileName)))
        ->toBe("<?php\n\nreturn ".VarExporter::export($translation->phrases->pluck('value', 'key')->toArray(), VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);

    File::deleteDirectory(lang_path());
});

test('export can handle PHP translation files', function () {
    App::useLangPath(__DIR__.'lang_test');

    if (! File::exists(lang_path('en'.DIRECTORY_SEPARATOR.'test.php'))) {
        File::makeDirectory(lang_path('en'), 0755, true);
        File::put(lang_path('en'.DIRECTORY_SEPARATOR.'test.php'), "<?php\n\nreturn ".VarExporter::export(['accepted' => 'The :attribute must be accepted.'], VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);
    }

    $filesystem = new FilesystemMock();

    $translation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'test', 'extension' => 'php']), 'file')
            ->state([
                'key' => 'accepted',
                'value' => 'The :attribute must be accepted.',
                'phrase_id' => null,
            ]))
        ->has(Language::factory(['code' => 'en']))
        ->create();

    $translationsManager = new TranslationsManager($filesystem);
    $translationsManager->export();

    $path = lang_path('en'.DIRECTORY_SEPARATOR.'test.php');
    $pathInDisk = lang_path($translation->language->code.DIRECTORY_SEPARATOR.'test.php');

    expect(File::get($path))->toBe(File::get($pathInDisk));

    File::deleteDirectory(lang_path());
});

test('export can handle JSON translation files', function () {
    App::useLangPath(__DIR__.'lang_test');

    if (! File::exists(lang_path('en'.DIRECTORY_SEPARATOR.'test.json'))) {
        File::makeDirectory(lang_path('en'), 0755, true);
        File::put(lang_path('en'.DIRECTORY_SEPARATOR.'test.json'), json_encode(['accepted' => 'The :attribute must be accepted.'], JSON_PRETTY_PRINT));
    }
    $filesystem = new FilesystemMock();

    $translation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'test', 'extension' => 'json']), 'file')
            ->state([
                'key' => 'accepted',
                'value' => 'The :attribute must be accepted.',
                'phrase_id' => null,
            ]))
        ->has(Language::factory(['code' => 'en']))
        ->create();

    $translationsManager = new TranslationsManager($filesystem);
    $translationsManager->export();

    $path = lang_path('en'.DIRECTORY_SEPARATOR.'test.json');
    $pathInDisk = lang_path($translation->language->code.DIRECTORY_SEPARATOR.'test.json');

    expect(File::get($path))->toBe(File::get($pathInDisk));

    File::deleteDirectory(lang_path());
});

it('returns the correct list of parameters for a given phrase', function () {
    $filesystem = new FilesystemMock();

    $translationsManager = new TranslationsManager($filesystem);
    $parameters = $translationsManager->getPhraseParameters('The :attribute must be accepted when :other is :value.');
    expect($parameters)->toBe(['attribute', 'other', 'value']);

    $parametersEmpty = $translationsManager->getPhraseParameters('');
    expect($parametersEmpty)->toBe(null);
});
