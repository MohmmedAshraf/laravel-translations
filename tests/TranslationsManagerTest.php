<?php

use Brick\VarExporter\VarExporter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Outhebox\TranslationsUI\Facades\TranslationsUI;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

beforeEach(function () {
    App::useLangPath(__DIR__.'/lang_test');
    createDirectoryIfNotExits(lang_path());
});

it('returns the correct list of locales', function () {
    createPhpLanguageFile('en/auth.php', []);
    createJsonLanguageFile('en.json', []);

    createJsonLanguageFile('fr.json', []);

    createPhpLanguageFile('de/validation.php', []);

    // Create nested folder structure
    createPhpLanguageFile('en/book/create.php', []);

    $locales = TranslationsUI::getLocales();

    expect($locales)->toBe(['de', 'en', 'fr']);
});

it('returns the correct translations for a given locale', function () {
    createPhpLanguageFile('en/auth.php', [
        'test' => 'Test',
    ]);
    createPhpLanguageFile('en/validation.php', [
        'test' => 'Test1',
    ]);
    createJsonLanguageFile('en.json', [
        'title' => 'My title',
    ]);

    // Update to include nested folder
    createPhpLanguageFile('en/book/create.php', [
        'nested' => 'Nested test',
    ]);

    createPhpLanguageFile('en/book/excluded.php', [
        'nested' => 'Nested test',
    ]);

    TranslationsUI::setExcludeFiles(['validation.php', 'book/excluded.php']);
    TranslationsUI::setSourceLanguage('en');

    $translations = TranslationsUI::getTranslations('en');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
        'en/auth.php' => ['test' => 'Test'],
        'en/book/create.php' => ['nested' => 'Nested test'],
    ]);

    $translations = TranslationsUI::getTranslations('');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
        'en/auth.php' => ['test' => 'Test'],
        'en/book/create.php' => ['nested' => 'Nested test'],
    ]);
});

it('it excludes the correct files', function () {
    createPhpLanguageFile('en/auth.php', [
        'test' => 'Test',
    ]);
    createPhpLanguageFile('en/validation.php', [
        'test' => 'Test1',
    ]);
    createJsonLanguageFile('en.json', [
        'title' => 'My title',
    ]);

    // Update to include nested folder
    createPhpLanguageFile('en/book/create.php', [
        'nested' => 'Nested test',
    ]);

    TranslationsUI::setExcludeFiles(['*.php']);
    TranslationsUI::setSourceLanguage('en');

    $translations = TranslationsUI::getTranslations('en');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
    ]);

    $translations = TranslationsUI::getTranslations('');
    expect($translations)->toBe([
        'en.json' => ['title' => 'My title'],
    ]);
});

test('export creates a new translation file with the correct content', function () {
    createDirectoryIfNotExits(lang_path('en/auth.php'));
    // Update to include nested folder
    createDirectoryIfNotExits(lang_path('en/book/create.php'));

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

    $nestedTranslation = Translation::factory([
        'source' => true,
        'language_id' => Language::factory([
            'code' => 'en',
            'name' => 'English',
        ]),
    ])->has(Phrase::factory()->state([
        'phrase_id' => null,
        'translation_file_id' => TranslationFile::factory([
            'name' => 'book/create',
            'extension' => 'php',
        ]),
    ]))->create();

    TranslationsUI::export();

    $fileName = lang_path('en/'.$translation->phrases[0]->file->name.'.'.$translation->phrases[0]->file->extension);
    $nestedFileName = lang_path('en/'.$nestedTranslation->phrases[0]->file->name.'.'.$nestedTranslation->phrases[0]->file->extension);

    $fileNameInDisk = File::allFiles(lang_path($translation->language->code))[0]->getPathname();
    $nestedFileNameInDisk = File::allFiles(lang_path($nestedTranslation->language->code))[1]->getPathname();

    expect($fileName)->toBe($fileNameInDisk)
        ->and(File::get($fileName))
        ->toBe("<?php\n\nreturn ".VarExporter::export($translation->phrases->pluck('value', 'key')->toArray(), VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL)
        ->and($nestedFileName)->toBe($nestedFileNameInDisk)
        ->and(File::get($nestedFileName))
        ->toBe("<?php\n\nreturn ".VarExporter::export($nestedTranslation->phrases->pluck('value', 'key')->toArray(), VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);

    File::deleteDirectory(lang_path());
});

test('export can handle PHP translation files', function () {
    createPhpLanguageFile('en/test.php', ['accepted' => 'The :attribute must be accepted.']);

    // Update to include nested folder
    createPhpLanguageFile('en/book/create.php', ['nested' => 'Nested :attribute must be accepted.']);

    $translation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'test', 'extension' => 'php']), 'file')
            ->state([
                'key' => 'accepted',
                'value' => 'The :attribute must be accepted.',
                'phrase_id' => null,
            ]))
        ->for(Language::factory()->state(['code' => 'en']))
        ->create();

    $nestedTranslation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'book/create', 'extension' => 'php']), 'file')
            ->state([
                'key' => 'nested',
                'value' => 'Nested :attribute must be accepted.',
                'phrase_id' => null,
            ]))
        ->for(Language::factory()->state(['code' => 'en']))
        ->create();

    TranslationsUI::export();

    $path = lang_path('en'.DIRECTORY_SEPARATOR.'test.php');
    $nestedPath = lang_path('en'.DIRECTORY_SEPARATOR.'book'.DIRECTORY_SEPARATOR.'create.php');
    $pathInDisk = lang_path($translation->language->code.DIRECTORY_SEPARATOR.'test.php');
    $nestedPathInDisk = lang_path($nestedTranslation->language->code.DIRECTORY_SEPARATOR.'book'.DIRECTORY_SEPARATOR.'create.php');

    expect(File::get($path))->toBe(File::get($pathInDisk))
        ->and(File::get($nestedPath))->toBe(File::get($nestedPathInDisk));

    File::deleteDirectory(lang_path());
});

test('export can handle JSON translation files', function () {
    createJsonLanguageFile('en/test.json', ['accepted' => 'The :attribute must be accepted.']);

    // Update to include nested folder
    createJsonLanguageFile('en/book/create.json', ['nested' => 'Nested test']);

    $translation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'test', 'extension' => 'json']), 'file')
            ->state([
                'key' => 'accepted',
                'value' => 'The :attribute must be accepted.',
                'phrase_id' => null,
            ]))
        ->for(Language::factory()->state(['code' => 'en']))
        ->create();

    $nestedTranslation = Translation::factory()
        ->has(Phrase::factory()
            ->for(TranslationFile::factory()->state(['name' => 'book/create', 'extension' => 'json']), 'file')
            ->state([
                'key' => 'nested',
                'value' => 'Nested test',
                'phrase_id' => null,
            ]))
        ->for(Language::factory()->state(['code' => 'en']))
        ->create();

    TranslationsUI::export();

    $path = lang_path('en'.DIRECTORY_SEPARATOR.'test.json');
    $nestedPath = lang_path('en'.DIRECTORY_SEPARATOR.'book'.DIRECTORY_SEPARATOR.'create.json');
    $pathInDisk = lang_path($translation->language->code.DIRECTORY_SEPARATOR.'test.json');
    $nestedPathInDisk = lang_path($nestedTranslation->language->code.DIRECTORY_SEPARATOR.'book'.DIRECTORY_SEPARATOR.'create.json');

    expect(File::get($path))->toBe(File::get($pathInDisk))
        ->and(File::get($nestedPath))->toBe(File::get($nestedPathInDisk));

    File::deleteDirectory(lang_path());
});

it('returns the correct list of parameters for a given phrase', function () {
    $parameters = getPhraseParameters('The :attribute must be accepted when :other is :value.');
    expect($parameters)->toBe(['attribute', 'other', 'value']);
    $parametersEmpty = getPhraseParameters('');
    expect($parametersEmpty)->toBe(null);
});

afterEach(function () {
    File::deleteDirectory(lang_path());
});
