<?php

use Brick\VarExporter\VarExporter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use Outhebox\LaravelTranslations\Models\TranslationFile;
use Outhebox\LaravelTranslations\Tests\FilesystemMock;
use Outhebox\LaravelTranslations\TranslationsManager;

beforeEach(function () {
    App::useLangPath(__DIR__ . 'lang_test');
    createDirectoryIfNotExits(lang_path());
});

it('returns the correct list of locales', function () {
    $filesystem = new Filesystem();
    // Create the source language directory
    createPhpLanguageFile(lang_path('en/auth.php'), []);
    createJsonLangaueFile(lang_path('en.json'), []);

    createJsonLangaueFile(lang_path('fr.json'), []);

    createPhpLanguageFile(lang_path('de/validation.php'), []);

    $translationsManager = new TranslationsManager($filesystem);
    $locales = $translationsManager->getLocales();

    expect($locales)->toBe(['de', 'en', 'fr']);
});

it('returns the correct translations for a given locale', function () {
    createPhpLanguageFile(lang_path('en/auth.php'), [
        'test' => 'Test',
    ]);
    createPhpLanguageFile(lang_path('en/validation.php'), [
        'test' => 'Test1',
    ]);
    createJsonLangaueFile(lang_path('en.json'), [
        'title' => 'My title',
    ]);

    Config::set('translations.exclude_files', ['validation.php']);
    Config::set('translations.source_language', 'en');
    $filesystem = new Filesystem();

    $translationsManager = new TranslationsManager($filesystem);
    $translations = $translationsManager->getTranslations('en');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
        'en/auth.php' => ['test' => 'Test']
    ]);

    $translations = $translationsManager->getTranslations('');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
        'en/auth.php' => ['test' => 'Test']
    ]);
});

it('it excludes the correct files', function () {
    createPhpLanguageFile(lang_path('en/auth.php'), [
        'test' => 'Test',
    ]);
    createPhpLanguageFile(lang_path('en/validation.php'), [
        'test' => 'Test1',
    ]);
    createJsonLangaueFile(lang_path('en.json'), [
        'title' => 'My title',
    ]);

    Config::set('translations.exclude_files', ['*.php']);
    Config::set('translations.source_language', 'en');
    $filesystem = new Filesystem();

    $translationsManager = new TranslationsManager($filesystem);
    $translations = $translationsManager->getTranslations('en');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
    ]);

    $translations = $translationsManager->getTranslations('');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
    ]);
});

test('export creates a new translation file with the correct content', function () {
    $filesystem = new Filesystem();
    createDirectoryIfNotExits(lang_path('en/auth.php'));

    $translation = Translation::factory([
        'source' => true,
        'language_id' => Language::factory([
            'code' => 'en',
            'name' => 'English',
        ]),
    ])->has(Phrase::factory()->state([
        'phrase_id' => null,
        'translation_file_id' => TranslationFile::factory([
            'name' => 'en/auth',
            'extension' => 'php',
        ]),
    ]))->create();

    $translationsManager = new TranslationsManager($filesystem);
    $translationsManager->export();

    $fileName =  lang_path($translation->phrases[0]->file->name . '.' . $translation->phrases[0]->file->extension);
    $fileNameInDisk = File::allFiles(lang_path($translation->language->code))[0]->getPathname();

    expect($fileName)->toBe($fileNameInDisk)
        ->and(File::get($fileName))
        ->toBe("<?php\n\nreturn " . VarExporter::export($translation->phrases->pluck('value', 'key')->toArray(), VarExporter::TRAILING_COMMA_IN_ARRAY) . ';' . PHP_EOL);

    File::deleteDirectory(lang_path());
});

test('export can handle PHP translation files', function () {
    createPhpLanguageFile('en/test.php', ['accepted' => 'The :attribute must be accepted.']);

    $filesystem = new Filesystem();

    $translation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'en/test', 'extension' => 'php']), 'file')
            ->state([
                'key' => 'accepted',
                'value' => 'The :attribute must be accepted.',
                'phrase_id' => null,
            ]))
        ->for(Language::factory()->state(['code' => 'en']))
        ->create();


    $translationsManager = new TranslationsManager($filesystem);
    $translationsManager->export();

    $path = lang_path('en' . DIRECTORY_SEPARATOR . 'test.php');
    $pathInDisk = lang_path($translation->language->code . DIRECTORY_SEPARATOR . 'test.php');

    expect(File::get($path))->toBe(File::get($pathInDisk));

    File::deleteDirectory(lang_path());
});

test('export can handle JSON translation files', function () {
    createJsonLangaueFile('en/test.json', ['accepted' => 'The :attribute must be accepted.']);
    $filesystem = new Filesystem();

    $translation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'en/test', 'extension' => 'json']), 'file')
            ->state([
                'key' => 'accepted',
                'value' => 'The :attribute must be accepted.',
                'phrase_id' => null,
            ]))
            ->for(Language::factory()->state(['code' => 'en']))
        ->create();

    $translationsManager = new TranslationsManager($filesystem);
    $translationsManager->export();

    $path = lang_path('en' . DIRECTORY_SEPARATOR . 'test.json');
    $pathInDisk = lang_path($translation->language->code . DIRECTORY_SEPARATOR . 'test.json');

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

afterEach(function () {
    File::deleteDirectory(lang_path());
});
