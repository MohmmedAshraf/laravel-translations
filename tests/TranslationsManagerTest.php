<?php

namespace Outhebox\TranslationsUI\Tests;

use Brick\VarExporter\VarExporter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;
use Outhebox\TranslationsUI\TranslationsManager;

class TranslationsManagerTest extends TestCase
{
    /** @test */
    public function it_returns_the_correct_list_of_locales(): void
    {
        $filesystem = new FilesystemMock();

        $translationsManager = new TranslationsManager($filesystem);
        $locales = $translationsManager->getLocales();

        $this->assertEquals(['en'], $locales);
    }

    /** @test */
    public function export_creates_a_new_translation_file_with_the_correct_content(): void
    {
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

        $this->assertEquals($fileName, $fileNameInDisk);
        $this->assertEquals("<?php\n\nreturn ".VarExporter::export($translation->phrases->pluck('value', 'key')->toArray(), VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL, File::get(lang_path($translation->language->code.DIRECTORY_SEPARATOR.$fileName)));

        File::deleteDirectory(lang_path());
    }

    /** @test */
    public function export_can_handle_php_translation_files(): void
    {
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

        $this->assertEquals(File::get($path), File::get($pathInDisk));

        File::deleteDirectory(lang_path());
    }

    /** @test */
    public function export_can_handle_json_translation_files(): void
    {
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

        $this->assertEquals(File::get($path), File::get($pathInDisk));

        File::deleteDirectory(lang_path());
    }

    /** @test */
    public function it_returns_the_correct_list_of_parameters_for_a_given_phrase(): void
    {
        $parameters = getPhraseParameters('The :attribute must be accepted when :other is :value.');
        $this->assertEquals(['attribute', 'other', 'value'], $parameters);

        $parametersEmpty = getPhraseParameters('');
        $this->assertEquals(null, $parametersEmpty);
    }
}
