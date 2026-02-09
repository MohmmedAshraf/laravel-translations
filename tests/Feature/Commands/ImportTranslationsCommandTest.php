<?php

use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\ImportLog;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);
});

it('imports PHP translation files', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->count())->toBeGreaterThan(0);
    expect(Group::query()->count())->toBeGreaterThan(0);
    expect(TranslationKey::query()->count())->toBeGreaterThan(0);
    expect(Translation::query()->count())->toBeGreaterThan(0);
});

it('imports JSON translation files', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    $jsonGroup = Group::query()->where('file_format', 'json')->first();
    expect($jsonGroup)->not->toBeNull();
    expect($jsonGroup->name)->toBe('_json');
});

it('creates languages from discovered locales', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->where('code', 'en')->exists())->toBeTrue();
    expect(Language::query()->where('code', 'fr')->exists())->toBeTrue();
    expect(Language::query()->where('code', 'ar')->exists())->toBeTrue();
});

it('creates groups from PHP file names', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Group::query()->where('name', 'auth')->exists())->toBeTrue();
    expect(Group::query()->where('name', 'messages')->exists())->toBeTrue();
    expect(Group::query()->where('name', 'validation')->exists())->toBeTrue();
});

it('creates an import log entry', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(ImportLog::query()->count())->toBe(1);

    $log = ImportLog::query()->first();
    expect($log->key_count)->toBeGreaterThan(0);
    expect($log->duration_ms)->toBeGreaterThan(0);
});

it('uses fresh option to delete existing data', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $firstCount = TranslationKey::query()->count();

    $this->artisan('translations:import', ['--fresh' => true, '--no-interaction' => true]);

    expect(TranslationKey::query()->count())->toBe($firstCount);
    expect(ImportLog::query()->count())->toBe(2);
});

it('respects no-overwrite option', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $translation = Translation::query()->whereNotNull('value')->first();
    $originalValue = $translation->value;

    $this->artisan('translations:import', ['--no-overwrite' => true, '--no-interaction' => true]);

    $translation->refresh();
    expect($translation->value)->toBe($originalValue);
});

it('marks source language correctly', function () {
    config(['translations.source_language' => 'en']);

    $this->artisan('translations:import', ['--no-interaction' => true]);

    $en = Language::query()->where('code', 'en')->first();
    expect($en->is_source)->toBeTrue();
});

it('sets correct translation status for imported values', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $translated = Translation::query()->whereNotNull('value')->first();
    expect($translated->status->value)->toBe('translated');
});

it('detects parameters in imported translations', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $key = TranslationKey::query()
        ->whereHas('group', fn ($q) => $q->where('name', 'auth'))
        ->where('key', 'throttle')
        ->first();

    expect($key->parameters)->toContain(':seconds');
});

it('detects HTML in imported translations', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $key = TranslationKey::query()
        ->whereHas('group', fn ($q) => $q->where('name', 'messages'))
        ->where('key', 'html_content')
        ->first();

    expect($key->is_html)->toBeTrue();
});

it('detects plural syntax in imported translations', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $key = TranslationKey::query()
        ->whereHas('group', fn ($q) => $q->where('name', 'messages'))
        ->where('key', 'plural')
        ->first();

    expect($key->is_plural)->toBeTrue();
});

it('replicates keys to all languages after import', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $languageCount = Language::query()->active()->count();
    $keyCount = TranslationKey::query()->count();

    expect(Translation::query()->count())->toBe($languageCount * $keyCount);
});

it('displays import results in table format', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->expectsOutputToContain('Import completed')
        ->assertSuccessful();
});

it('handles flattened nested PHP arrays', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $key = TranslationKey::query()
        ->whereHas('group', fn ($q) => $q->where('name', 'validation'))
        ->where('key', 'custom.email.required')
        ->first();

    expect($key)->not->toBeNull();
});

it('handles import with empty lang directory', function () {
    config(['translations.lang_path' => sys_get_temp_dir().'/empty_lang_'.uniqid()]);

    $path = config('translations.lang_path');
    mkdir($path, 0755, true);

    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    rmdir($path);
});

it('creates French translations correctly', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $fr = Language::query()->where('code', 'fr')->first();
    $authGroup = Group::query()->where('name', 'auth')->first();

    $key = TranslationKey::query()
        ->where('group_id', $authGroup->id)
        ->where('key', 'failed')
        ->first();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $fr->id)
        ->first();

    expect($translation->value)->toContain('identifiants');
});

it('handles import of mixed parameter styles in JSON', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $jsonGroup = Group::query()->where('file_format', 'json')->first();

    $key = TranslationKey::query()
        ->where('group_id', $jsonGroup->id)
        ->where('key', 'Welcome, :name')
        ->first();

    expect($key)->not->toBeNull();
    expect($key->parameters)->toContain(':name');
});

it('creates multiple import logs for successive imports', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);
    $this->artisan('translations:import', ['--no-interaction' => true]);

    expect(ImportLog::query()->count())->toBe(2);
});

it('activates pre-existing inactive languages on import', function () {
    Language::factory()->create(['code' => 'en', 'active' => false, 'is_source' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => false]);

    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->where('code', 'en')->first()->active)->toBeTrue();
    expect(Language::query()->where('code', 'fr')->first()->active)->toBeTrue();
});

it('handles both PHP and JSON for same locale', function () {
    $this->artisan('translations:import', ['--no-interaction' => true]);

    $en = Language::query()->where('code', 'en')->first();

    $phpTranslation = Translation::query()
        ->where('language_id', $en->id)
        ->whereHas('translationKey', fn ($q) => $q->whereHas('group', fn ($g) => $g->where('file_format', 'php')))
        ->count();

    $jsonTranslation = Translation::query()
        ->where('language_id', $en->id)
        ->whereHas('translationKey', fn ($q) => $q->whereHas('group', fn ($g) => $g->where('file_format', 'json')))
        ->count();

    expect($phpTranslation)->toBeGreaterThan(0);
    expect($jsonTranslation)->toBeGreaterThan(0);
});
