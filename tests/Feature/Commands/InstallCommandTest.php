<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

it('runs migrations successfully', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();
});

it('seeds default languages', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->count())->toBeGreaterThanOrEqual(10);
    expect(Language::query()->where('code', 'en')->exists())->toBeTrue();
    expect(Language::query()->where('code', 'ar')->exists())->toBeTrue();
});

it('creates default contributor in non-interactive mode', function () {
    config(['translations.auth.driver' => 'contributors']);

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);

    $contributor = Contributor::query()->first();
    expect($contributor->email)->toBe('admin@translations.local');
    expect($contributor->role->value)->toBe('owner');
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
