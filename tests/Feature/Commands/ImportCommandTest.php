<?php

use Outhebox\Translations\Models\ImportLog;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);
});

it('imports translations from lang path', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('Import completed');

    expect(TranslationKey::query()->count())->toBeGreaterThan(0)
        ->and(ImportLog::query()->count())->toBe(1);
});

it('imports fresh deleting all existing data', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    $firstCount = TranslationKey::query()->count();

    $this->artisan('translations:import', ['--no-interaction' => true, '--fresh' => true])
        ->assertSuccessful();

    expect(TranslationKey::query()->count())->toBe($firstCount)
        ->and(ImportLog::query()->count())->toBe(2);
});

it('imports without overwriting existing translations', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    $this->artisan('translations:import', ['--no-interaction' => true, '--no-overwrite' => true])
        ->assertSuccessful();

    expect(ImportLog::query()->count())->toBe(2);
});

it('creates languages from fixtures', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->where('code', 'en')->exists())->toBeTrue();
});

it('displays results table', function () {
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('Import completed');
});
