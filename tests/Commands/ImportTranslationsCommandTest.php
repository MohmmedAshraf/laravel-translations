<?php

use Illuminate\Support\Facades\File;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;

beforeEach(function () {
    App::useLangPath(__DIR__.'lang_test');
    File::deleteDirectory(lang_path());
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

it('imports non-source locale phrases under the correct translation model', function () {
    // Given
    $english = Language::factory()->create([
        'rtl' => false,
        'code' => 'en',
        'name' => 'English',
    ]);

    $french = Language::factory()->create([
        'rtl' => false,
        'code' => 'fr',
        'name' => 'French',
    ]);

    createPhpLanguageFile('en/messages.php', [
        'welcome' => 'Welcome',
        'goodbye' => 'Goodbye',
    ]);

    createPhpLanguageFile('fr/messages.php', [
        'welcome' => 'Bienvenue',
        'goodbye' => 'Au revoir',
    ]);

    Phrase::bootHasUuid();

    // When
    $this->artisan('translations:import')
        ->assertExitCode(0);

    // Then
    $sourceTranslation = Translation::where('source', true)
        ->where('language_id', $english->id)
        ->first();

    $frenchTranslation = Translation::where('source', false)
        ->where('language_id', $french->id)
        ->first();

    expect($sourceTranslation)->not->toBeNull();
    expect($frenchTranslation)->not->toBeNull();

    $frenchPhrases = $frenchTranslation->phrases;
    expect($frenchPhrases)->toHaveCount(2);

    $welcomePhrase = $frenchPhrases->where('key', 'welcome')->first();
    expect($welcomePhrase->value)->toBe('Bienvenue');
    expect($welcomePhrase->translation_id)->toBe($frenchTranslation->id);
    expect($welcomePhrase->translation_id)->not->toBe($sourceTranslation->id);
});

it('syncs missing translations from source to non-source locales', function () {
    // Given
    $english = Language::factory()->create([
        'rtl' => false,
        'code' => 'en',
        'name' => 'English',
    ]);

    $spanish = Language::factory()->create([
        'rtl' => false,
        'code' => 'es',
        'name' => 'Spanish',
    ]);

    createPhpLanguageFile('en/messages.php', [
        'welcome' => 'Welcome',
        'goodbye' => 'Goodbye',
    ]);

    // Spanish only has 'welcome', missing 'goodbye'
    createPhpLanguageFile('es/messages.php', [
        'welcome' => 'Bienvenido',
    ]);

    Phrase::bootHasUuid();

    // When
    $this->artisan('translations:import')
        ->assertExitCode(0);

    // Then
    $spanishTranslation = Translation::where('source', false)
        ->where('language_id', $spanish->id)
        ->first();

    expect($spanishTranslation)->not->toBeNull();

    $phrases = $spanishTranslation->phrases;
    expect($phrases)->toHaveCount(2);

    // The existing key should have the Spanish value
    expect($phrases->where('key', 'welcome')->first()->value)->toBe('Bienvenido');

    // The missing key should be synced with a null value
    expect($phrases->where('key', 'goodbye')->first()->value)->toBeNull();
});

it('imports multiple non-source locales each under their own translation model', function () {
    // Given
    $english = Language::factory()->create([
        'rtl' => false,
        'code' => 'en',
        'name' => 'English',
    ]);

    $french = Language::factory()->create([
        'rtl' => false,
        'code' => 'fr',
        'name' => 'French',
    ]);

    $german = Language::factory()->create([
        'rtl' => false,
        'code' => 'de',
        'name' => 'German',
    ]);

    createPhpLanguageFile('en/messages.php', [
        'greeting' => 'Hello',
    ]);

    createPhpLanguageFile('fr/messages.php', [
        'greeting' => 'Bonjour',
    ]);

    createPhpLanguageFile('de/messages.php', [
        'greeting' => 'Hallo',
    ]);

    Phrase::bootHasUuid();

    // When
    $this->artisan('translations:import')
        ->assertExitCode(0);

    // Then
    $sourceTranslation = Translation::where('source', true)->first();
    $frenchTranslation = Translation::where('language_id', $french->id)->where('source', false)->first();
    $germanTranslation = Translation::where('language_id', $german->id)->where('source', false)->first();

    expect($sourceTranslation)->not->toBeNull();
    expect($frenchTranslation)->not->toBeNull();
    expect($germanTranslation)->not->toBeNull();

    // Each translation should be a separate record
    expect($frenchTranslation->id)->not->toBe($sourceTranslation->id);
    expect($germanTranslation->id)->not->toBe($sourceTranslation->id);
    expect($frenchTranslation->id)->not->toBe($germanTranslation->id);

    // Each should have the correct phrase value
    expect($frenchTranslation->phrases->where('key', 'greeting')->first()->value)->toBe('Bonjour');
    expect($germanTranslation->phrases->where('key', 'greeting')->first()->value)->toBe('Hallo');
    expect($sourceTranslation->phrases->where('key', 'greeting')->first()->value)->toBe('Hello');
});

it('imports JSON translations for non-source locales correctly', function () {
    // Given
    $english = Language::factory()->create([
        'rtl' => false,
        'code' => 'en',
        'name' => 'English',
    ]);

    $spanish = Language::factory()->create([
        'rtl' => false,
        'code' => 'es',
        'name' => 'Spanish',
    ]);

    createJsonLanguageFile('en.json', [
        'Welcome' => 'Welcome',
        'Logout' => 'Logout',
    ]);

    createJsonLanguageFile('es.json', [
        'Welcome' => 'Bienvenido',
        'Logout' => 'Cerrar sesión',
    ]);

    Phrase::bootHasUuid();

    // When
    $this->artisan('translations:import')
        ->assertExitCode(0);

    // Then
    $spanishTranslation = Translation::where('source', false)
        ->where('language_id', $spanish->id)
        ->first();

    expect($spanishTranslation)->not->toBeNull();

    $phrases = $spanishTranslation->phrases;
    expect($phrases->where('key', 'Welcome')->first()->value)->toBe('Bienvenido');
    expect($phrases->where('key', 'Logout')->first()->value)->toBe('Cerrar sesión');
});
