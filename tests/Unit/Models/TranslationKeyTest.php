<?php

use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('detects when key has parameters', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute', ':max']]);

    expect($key->hasParameters())->toBeTrue();
});

it('returns false for hasParameters when empty', function () {
    $key = TranslationKey::factory()->create(['parameters' => null]);

    expect($key->hasParameters())->toBeFalse();
});

it('returns parameter names', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name', ':count']]);

    expect($key->parameterNames())->toBe([':name', ':count']);
});

it('returns empty array for parameterNames when null', function () {
    $key = TranslationKey::factory()->create(['parameters' => null]);

    expect($key->parameterNames())->toBe([]);
});

it('scopes to keys in a specific group', function () {
    $key1 = TranslationKey::factory()->create();
    $key2 = TranslationKey::factory()->create();

    $results = TranslationKey::query()->inGroup($key1->group_id)->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($key1->id);
});

it('scopes to keys with missing translations for a language', function () {
    $language = Language::factory()->create();
    $key1 = TranslationKey::factory()->create();
    $key2 = TranslationKey::factory()->create();
    $key3 = TranslationKey::factory()->create();

    Translation::factory()->create([
        'translation_key_id' => $key1->id,
        'language_id' => $language->id,
        'status' => 'translated',
        'value' => 'Translated',
    ]);

    Translation::factory()->create([
        'translation_key_id' => $key2->id,
        'language_id' => $language->id,
        'status' => 'untranslated',
        'value' => null,
    ]);

    $missing = TranslationKey::query()->withMissingTranslations($language->id)->get();

    expect($missing->pluck('id')->all())->toContain($key2->id)
        ->toContain($key3->id)
        ->not->toContain($key1->id);
});
