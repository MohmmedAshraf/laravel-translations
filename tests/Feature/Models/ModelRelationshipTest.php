<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('language has many translations', function () {
    $language = Language::factory()->create(['code' => 'en']);
    $key = TranslationKey::factory()->create();
    Translation::factory()->create(['language_id' => $language->id, 'translation_key_id' => $key->id]);

    expect($language->translations)->toHaveCount(1);
});

it('language belongs to many contributors', function () {
    $language = Language::factory()->create(['code' => 'en']);
    $contributor = Contributor::factory()->create();
    $contributor->languages()->attach($language);

    expect($language->contributors)->toHaveCount(1);
    expect($contributor->languages)->toHaveCount(1);
});

it('group has many translation keys', function () {
    $group = Group::factory()->create();
    TranslationKey::factory()->count(3)->create(['group_id' => $group->id]);

    expect($group->translationKeys)->toHaveCount(3);
});

it('translation key belongs to group', function () {
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->create(['group_id' => $group->id]);

    expect($key->group->id)->toBe($group->id);
});

it('translation key has many translations', function () {
    $key = TranslationKey::factory()->create();
    $en = Language::factory()->create(['code' => 'en']);
    $fr = Language::factory()->create(['code' => 'fr']);

    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $en->id]);
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $fr->id]);

    expect($key->translations)->toHaveCount(2);
});

it('translation belongs to translation key and language', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'en']);
    $translation = Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
    ]);

    expect($translation->translationKey->id)->toBe($key->id);
    expect($translation->language->id)->toBe($language->id);
});

it('cascades deletes from language to translations', function () {
    $language = Language::factory()->create(['code' => 'en']);
    $key = TranslationKey::factory()->create();
    Translation::factory()->create(['language_id' => $language->id, 'translation_key_id' => $key->id]);

    $language->delete();

    expect(Translation::query()->where('language_id', $language->id)->count())->toBe(0);
});

it('cascades deletes from group to translation keys', function () {
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->create(['group_id' => $group->id]);
    $language = Language::factory()->create(['code' => 'en']);
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $language->id]);

    $group->delete();

    expect(TranslationKey::query()->where('group_id', $group->id)->count())->toBe(0);
    expect(Translation::query()->where('translation_key_id', $key->id)->count())->toBe(0);
});
