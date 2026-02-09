<?php

use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('shows warning when no keys exist', function () {
    $this->artisan('translations:status')
        ->expectsOutputToContain('No translation keys found')
        ->assertSuccessful();
});

it('shows status for all languages', function () {
    $en = Language::factory()->create(['code' => 'en', 'name' => 'English']);
    $fr = Language::factory()->create(['code' => 'fr', 'name' => 'Français']);
    $key = TranslationKey::factory()->create();

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
        'status' => TranslationStatus::Translated,
    ]);
    Translation::factory()->untranslated()->create([
        'translation_key_id' => $key->id,
        'language_id' => $fr->id,
    ]);

    $this->artisan('translations:status')
        ->expectsOutputToContain('en')
        ->expectsOutputToContain('fr')
        ->assertSuccessful();
});

it('filters by locale option', function () {
    $en = Language::factory()->create(['code' => 'en']);
    Language::factory()->create(['code' => 'fr']);
    $key = TranslationKey::factory()->create();

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
    ]);

    $this->artisan('translations:status', ['--locale' => 'en'])
        ->expectsOutputToContain('en')
        ->assertSuccessful();
});

it('shows correct total keys count', function () {
    Language::factory()->create(['code' => 'en']);
    TranslationKey::factory()->count(5)->create();

    $this->artisan('translations:status')
        ->expectsOutputToContain('Total keys: 5')
        ->assertSuccessful();
});

it('shows warning when no active languages found', function () {
    TranslationKey::factory()->create();
    Language::factory()->create(['code' => 'en', 'active' => false]);

    $this->artisan('translations:status')
        ->expectsOutputToContain('No active languages found')
        ->assertSuccessful();
});
