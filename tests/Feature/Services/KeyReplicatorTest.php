<?php

use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\KeyReplicator;

beforeEach(function () {
    $this->replicator = new KeyReplicator;
});

it('replicates a key to all active languages', function () {
    $key = TranslationKey::factory()->create();
    Language::factory()->create(['code' => 'en', 'active' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->replicator->replicateKey($key);

    expect(Translation::query()->where('translation_key_id', $key->id)->count())->toBe(2);
});

it('skips languages that already have translations', function () {
    $key = TranslationKey::factory()->create();
    $en = Language::factory()->create(['code' => 'en', 'active' => true]);
    $fr = Language::factory()->create(['code' => 'fr', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
    ]);

    $this->replicator->replicateKey($key);

    expect(Translation::query()->where('translation_key_id', $key->id)->count())->toBe(2);
});

it('creates untranslated status for replicated translations', function () {
    $key = TranslationKey::factory()->create();
    $en = Language::factory()->create(['code' => 'en', 'active' => true]);

    $this->replicator->replicateKey($key);

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $en->id)
        ->first();

    expect($translation->status)->toBe(TranslationStatus::Untranslated);
    expect($translation->value)->toBeNull();
});

it('replicates for a new language', function () {
    $key1 = TranslationKey::factory()->create();
    $key2 = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'de']);

    $this->replicator->replicateForLanguage($language);

    expect(Translation::query()->where('language_id', $language->id)->count())->toBe(2);
});

it('replicates all keys to all active languages', function () {
    TranslationKey::factory()->count(3)->create();
    Language::factory()->create(['code' => 'en', 'active' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => true]);
    Language::factory()->create(['code' => 'de', 'active' => false]);

    $this->replicator->replicateAll();

    expect(Translation::query()->count())->toBe(6);
});

it('does not duplicate existing translations when replicating all', function () {
    $key = TranslationKey::factory()->create();
    $en = Language::factory()->create(['code' => 'en', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
    ]);

    $this->replicator->replicateAll();

    expect(Translation::query()->count())->toBe(1);
});

it('handles no keys gracefully', function () {
    Language::factory()->create(['code' => 'en', 'active' => true]);

    $this->replicator->replicateAll();

    expect(Translation::query()->count())->toBe(0);
});
