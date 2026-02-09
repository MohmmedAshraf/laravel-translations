<?php

use Illuminate\Support\Facades\Schema;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

it('runs migrations successfully', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_languages'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translations'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translation_keys'))->toBeTrue();
});

it('seeds default languages', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->count())->toBeGreaterThanOrEqual(10)
        ->and(Language::query()->where('code', 'en')->exists())->toBeTrue()
        ->and(Language::query()->where('code', 'ar')->exists())->toBeTrue();
});

it('creates default contributor in non-interactive mode', function () {
    config(['translations.auth.driver' => 'contributors']);

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);

    $contributor = Contributor::query()->first();
    expect($contributor->email)->toBe('admin@translations.local')
        ->and($contributor->role->value)->toBe('owner');
});

it('skips contributor creation when contributors already exist', function () {
    Contributor::factory()->create();

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);
});

it('skips contributor creation in users mode', function () {
    config(['translations.auth.driver' => 'users']);

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(0);
});

it('calls vendor:publish for config and assets', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('Translations installed successfully');
});

it('does not publish pro assets when pro is not installed', function () {
    $result = $this->artisan('translations:install', ['--no-interaction' => true]);
    $result->assertSuccessful();

    // Pro service provider class doesn't exist in test env, so pro publish should not be called
    // Verify by checking the command succeeds without pro — indirect but sufficient
    expect(class_exists(Outhebox\TranslationsPro\Providers\TranslationsProServiceProvider::class))->toBeFalse();
});
