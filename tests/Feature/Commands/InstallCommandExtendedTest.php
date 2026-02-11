<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\TranslationKey;

it('runs import when --import flag is passed', function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);

    $this->artisan('translations:install', [
        '--no-interaction' => true,
        '--import' => true,
    ])->assertSuccessful();

    expect(TranslationKey::query()->count())->toBeGreaterThan(0);
});

it('does not import when --import flag is not passed', function () {
    $this->artisan('translations:install', [
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(TranslationKey::query()->count())->toBe(0);
});

it('skips contributor creation when contributors already exist with contributor driver', function () {
    config(['translations.auth.driver' => 'contributors']);
    Contributor::factory()->create();

    $this->artisan('translations:install', [
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);
});
