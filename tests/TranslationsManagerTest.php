<?php

use Brick\VarExporter\VarExporter;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use Outhebox\LaravelTranslations\Models\TranslationFile;
use Outhebox\LaravelTranslations\TranslationsManager;

class FilesystemMock extends Filesystem
{
    public function directories($dir)
    {
        return ['es', 'vendor'];
    }

    public function allFiles($directory, $hidden = false): array
    {
        return [new SplFileInfo('auth.php'), new SplFileInfo('validation.json')];
    }
}

it('can get Locales', function () {
    $filesystem = new FilesystemMock();
    $locales = (new TranslationsManager($filesystem))->getLocales();
    expect(['es'])->toBe($locales);
});

it('can get Translations', function () {
    Config::set('translations.exclude_files', ['es']);
    $filesystem = new FilesystemMock();

    $translations = (new TranslationsManager($filesystem))->getTranslations('es');
    expect(['auth.php' => [], 'validation.json' => []])->toBe($translations);

    $translations = (new TranslationsManager($filesystem))->getTranslations('');
    expect(['auth.php' => [], 'validation.json' => []])->toBe($translations);
});

it('can export file', function () {
    App::useLangPath(__DIR__.'lang_test');

    $filesystem = new FilesystemMock();

    $translation = Translation::factory()->has(Phrase::factory()->state(['phrase_id' => null]))->create();

    (new TranslationsManager($filesystem))->export();

    $file_name = $translation->phrases[0]->file->name.'.'.$translation->phrases[0]->file->extension;
    $file_name_in_disk = File::allFiles(lang_path($translation->language->code))[0]->getFilename();

    expect($file_name)->toBe($file_name_in_disk);
    expect(File::get(lang_path($translation->language->code.DIRECTORY_SEPARATOR.$file_name)))->toBe("<?php\n\nreturn [\n\n]; ".PHP_EOL);

    File::deleteDirectory(lang_path());
});

it('can export file php', function () {
    App::useLangPath(__DIR__.'lang_test');

    if (! File::exists(lang_path('es'.DIRECTORY_SEPARATOR.'test.php'))) {
        File::makeDirectory(lang_path('es'), 0755, true);
        File::put(lang_path('es'.DIRECTORY_SEPARATOR.'test.php'), "<?php\n\nreturn ".VarExporter::export(['accepted' => 'El :attribute debe ser aceptado.'], VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);
    }

    $filesystem = new FilesystemMock();

    $translation = Translation::factory()
                              ->has(Phrase::factory()
                                          ->for(TranslationFile::factory()->state(['name' => 'test', 'extension' => 'php']), 'file')
                                          ->state(
                                              [
                                                  'key' => 'accepted',
                                                  'value' => 'El :attribute debe ser aceptado.',
                                                  'phrase_id' => null,
                                              ]
                                          ))
                              ->has(Language::factory(['code' => 'es']))
                              ->create();

    (new TranslationsManager($filesystem))->export();

    $path = lang_path('es'.DIRECTORY_SEPARATOR.'test.php');
    $path_in_disk = lang_path($translation->language->code.DIRECTORY_SEPARATOR.'test.php');
    expect(File::get($path))->toBe(File::get($path_in_disk));

    File::deleteDirectory(lang_path());
});

it('can export file json', function () {
    App::useLangPath(__DIR__.'lang_test');

    if (! File::exists(lang_path('es'.DIRECTORY_SEPARATOR.'test.json'))) {
        File::makeDirectory(lang_path('es'), 0755, true);
        File::put(lang_path('es'.DIRECTORY_SEPARATOR.'test.json'), json_encode(['accepted' => 'El :attribute debe ser aceptado.'], JSON_PRETTY_PRINT));
    }

    $filesystem = new FilesystemMock();

    $translation = Translation::factory()
                              ->has(Phrase::factory()
                                          ->for(TranslationFile::factory()->state(['name' => 'test', 'extension' => 'json']), 'file')
                                          ->state(
                                              [
                                                  'key' => 'accepted',
                                                  'value' => 'El :attribute debe ser aceptado.',
                                                  'phrase_id' => null,
                                              ]
                                          ))
                              ->has(Language::factory(['code' => 'es']))
                              ->create();

    (new TranslationsManager($filesystem))->export();

    $path = lang_path('es'.DIRECTORY_SEPARATOR.'test.json');
    $path_in_disk = lang_path($translation->language->code.DIRECTORY_SEPARATOR.'test.json');
    expect(File::get($path))->toBe(File::get($path_in_disk));

    File::deleteDirectory(lang_path());
});

it('can build phrases tree', function () {
    $filesystem = new FilesystemMock();

    $parameters = (new TranslationsManager($filesystem))->getPhraseParameters('The :attribute must be accepted when :other is :value.');
    expect(['attribute', 'other', 'value'])->toBe($parameters);

    $parameters_empty = (new TranslationsManager($filesystem))->getPhraseParameters('');
    expect(null)->toBe($parameters_empty);
});
