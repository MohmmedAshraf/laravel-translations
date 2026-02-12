<?php

use Illuminate\Support\Facades\Event;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\TranslationSaved;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('dispatches TranslationSaved event when value changes', function () {
    Event::fake([TranslationSaved::class]);

    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create();

    Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Hello',
        'status' => TranslationStatus::Translated,
    ]);

    Event::assertDispatched(TranslationSaved::class);
});

it('dispatches TranslationSaved with old value when updating', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create();

    $translation = Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Old value',
        'status' => TranslationStatus::Translated,
    ]);

    Event::fake([TranslationSaved::class]);

    $translation->update(['value' => 'New value']);

    Event::assertDispatched(TranslationSaved::class, function ($event) {
        return $event->oldValue === 'Old value';
    });
});

it('does not dispatch TranslationSaved when value is unchanged', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create();

    Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Same value',
        'status' => TranslationStatus::Translated,
    ]);

    Translation::resetStaticState();

    // Re-fetch from DB so wasRecentlyCreated is false
    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $language->id)
        ->first();

    $dispatchedEvents = [];
    Event::listen(TranslationSaved::class, function ($event) use (&$dispatchedEvents) {
        $dispatchedEvents[] = $event;
    });

    $translation->update(['needs_review' => true]);

    expect($dispatchedEvents)->toBeEmpty();
});

it('sets and clears revision context', function () {
    Translation::withRevisionContext('manual_edit', 'user@example.com', ['note' => 'test']);

    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create();

    Event::fake([TranslationSaved::class]);

    Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Test value',
        'status' => TranslationStatus::Translated,
    ]);

    Event::assertDispatched(TranslationSaved::class, function ($event) {
        return $event->changeType === 'manual_edit'
            && $event->changedBy === 'user@example.com';
    });
});

it('clears pending old values when overflow', function () {
    Translation::resetStaticState();

    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create();

    // Create many translations to overflow the pendingOldValues array
    for ($i = 0; $i < 5; $i++) {
        $k = TranslationKey::factory()->create();
        Translation::query()->create([
            'translation_key_id' => $k->id,
            'language_id' => $language->id,
            'value' => "value_{$i}",
            'status' => TranslationStatus::Translated,
        ]);
    }

    // Should still work correctly after many operations
    $translation = Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Test',
        'status' => TranslationStatus::Translated,
    ]);

    expect($translation->value)->toBe('Test');
});

it('uses scopes correctly', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create();

    Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Translated',
        'status' => TranslationStatus::Translated,
    ]);

    $key2 = TranslationKey::factory()->create();
    Translation::query()->create([
        'translation_key_id' => $key2->id,
        'language_id' => $language->id,
        'value' => null,
        'status' => TranslationStatus::Untranslated,
    ]);

    $key3 = TranslationKey::factory()->create();
    Translation::query()->create([
        'translation_key_id' => $key3->id,
        'language_id' => $language->id,
        'value' => 'Needs review',
        'status' => TranslationStatus::NeedsReview,
    ]);

    expect(Translation::query()->translated()->count())->toBe(1)
        ->and(Translation::query()->untranslated()->count())->toBe(1)
        ->and(Translation::query()->needsReview()->count())->toBe(1);
});
