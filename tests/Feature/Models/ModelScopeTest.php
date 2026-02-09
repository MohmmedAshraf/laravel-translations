<?php

use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('filters active languages', function () {
    Language::factory()->create(['code' => 'en', 'active' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => true]);
    Language::factory()->create(['code' => 'de', 'active' => false]);

    expect(Language::query()->active()->count())->toBe(2);
});

it('filters source language', function () {
    Language::factory()->create(['code' => 'en', 'is_source' => true]);
    Language::factory()->create(['code' => 'fr', 'is_source' => false]);

    expect(Language::query()->source()->count())->toBe(1);
    expect(Language::source()->code)->toBe('en');
});

it('filters translation keys by group', function () {
    $group1 = Group::factory()->create();
    $group2 = Group::factory()->create();

    TranslationKey::factory()->count(3)->create(['group_id' => $group1->id]);
    TranslationKey::factory()->count(2)->create(['group_id' => $group2->id]);

    expect(TranslationKey::query()->inGroup($group1->id)->count())->toBe(3);
    expect(TranslationKey::query()->inGroup($group2->id)->count())->toBe(2);
});

it('filters translated translations', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'en']);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'status' => TranslationStatus::Translated,
    ]);

    Translation::factory()->create([
        'translation_key_id' => TranslationKey::factory()->create()->id,
        'language_id' => $language->id,
        'status' => TranslationStatus::Untranslated,
    ]);

    expect(Translation::query()->translated()->count())->toBe(1);
    expect(Translation::query()->untranslated()->count())->toBe(1);
});

it('filters needs review translations', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'en']);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'status' => TranslationStatus::NeedsReview,
    ]);

    expect(Translation::query()->needsReview()->count())->toBe(1);
});

it('identifies source language via helper', function () {
    $source = Language::factory()->create(['code' => 'en', 'is_source' => true]);
    $other = Language::factory()->create(['code' => 'fr', 'is_source' => false]);

    expect($source->isSource())->toBeTrue();
    expect($other->isSource())->toBeFalse();
});

it('identifies json groups', function () {
    $json = Group::factory()->json()->create();
    $php = Group::factory()->create();

    expect($json->isJson())->toBeTrue();
    expect($php->isJson())->toBeFalse();
});

it('returns display name for groups', function () {
    $plain = Group::factory()->create(['name' => 'auth']);
    $vendor = Group::factory()->vendor('package')->create(['name' => 'messages']);

    expect($plain->displayName())->toBe('auth');
    expect($vendor->displayName())->toBe('package::messages');
});
