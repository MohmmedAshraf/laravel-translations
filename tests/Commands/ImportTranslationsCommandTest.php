<?php

use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;

beforeEach(function () {
    App::useLangPath(__DIR__.'lang_test');
    createDirectoryIfNotExits(lang_path());
});

it('can import translations with same keys in diffent groups', function () {
    // Given
    $english = Language::factory()
        ->create([
            'rtl' => false,
            'code' => 'en',
            'name' => 'English',
        ]);

    $dutch = Language::factory()
        ->create([
            'rtl' => false,
            'code' => 'nl',
            'name' => 'Nederlands',
        ]);

    createPhpLanguageFile('en/authors.php', [
        'title' => 'Authors',
    ]);

    createPhpLanguageFile('en/books.php', [
        'title' => 'Books',
    ]);

    createPhpLanguageFile('nl/unrelated.php', []);

    Phrase::bootHasUuid(); // IDK why this is needed... without this we get an integrity constraint violation for empty uuid.

    // When
    $this->artisan('translations:import')
        ->assertExitCode(0);

    // Then
    $translation = $dutch->translation;
    expect($translation)->toBeInstanceOf(Translation::class);

    $phrases = $translation->phrases;
    expect($phrases)->toHaveCount(2);
});
