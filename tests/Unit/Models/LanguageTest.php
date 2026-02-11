<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('identifies source language', function () {
    $source = Language::factory()->source()->create();
    $nonSource = Language::factory()->create(['is_source' => false]);

    expect($source->isSource())->toBeTrue();
    expect($nonSource->isSource())->toBeFalse();
});

it('finds the source language statically', function () {
    $source = Language::factory()->source()->create();
    Language::factory()->create(['is_source' => false]);

    expect(Language::source()->id)->toBe($source->id);
});

it('returns null when no source language exists', function () {
    Language::factory()->create(['is_source' => false]);

    expect(Language::source())->toBeNull();
});

it('scopes to active languages', function () {
    Language::factory()->create(['active' => true]);
    Language::factory()->create(['active' => true]);
    Language::factory()->create(['active' => false]);

    expect(Language::query()->active()->count())->toBe(2);
});

it('has translations relationship', function () {
    $language = Language::factory()->create();
    $key = TranslationKey::factory()->create();
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
    ]);

    expect($language->translations()->count())->toBe(1);
});

it('has contributors relationship', function () {
    $language = Language::factory()->create();
    $contributor = Contributor::factory()->create();
    $language->contributors()->attach($contributor);

    expect($language->contributors()->count())->toBe(1);
});
