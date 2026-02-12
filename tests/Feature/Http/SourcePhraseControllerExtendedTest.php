<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
    $this->sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
});

it('shows a single source phrase', function () {
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'login']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
        'value' => 'Log in',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.show', $key))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/source-language/show')
            ->has('translationKey')
            ->has('sourceLanguage')
            ->has('groups')
        );
});

it('returns 404 when no source language exists for show', function () {
    $this->sourceLanguage->update(['is_source' => false]);

    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.show', $key))
        ->assertNotFound();
});

it('returns 404 when no source language exists for index', function () {
    $this->sourceLanguage->update(['is_source' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertNotFound();
});

it('stores a new translation key', function () {
    $group = Group::factory()->create(['name' => 'auth']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.source.store'), [
            'group_id' => $group->id,
            'key' => 'new_key',
            'value' => 'New value with :attribute',
        ])
        ->assertRedirect(route('ltu.source.index'));

    $key = TranslationKey::query()->where('key', 'new_key')->first();
    expect($key)->not->toBeNull();
    expect($key->group_id)->toBe($group->id);
    expect($key->parameters)->toContain(':attribute');
});

it('stores a new key without value', function () {
    $group = Group::factory()->create(['name' => 'auth']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.source.store'), [
            'group_id' => $group->id,
            'key' => 'empty_key',
        ])
        ->assertRedirect(route('ltu.source.index'));

    $key = TranslationKey::query()->where('key', 'empty_key')->first();
    expect($key)->not->toBeNull();
    expect($key->parameters)->toBeNull();
});

it('updates a source phrase value', function () {
    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.source.update', $key), [
            'value' => 'Updated source value',
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->sourceLanguage->id)
        ->first();

    expect($translation->value)->toBe('Updated source value');
});

it('updates a source phrase key name', function () {
    $key = TranslationKey::factory()->create(['key' => 'old_key']);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.source.update', $key), [
            'key' => 'new_key',
        ])
        ->assertRedirect();

    expect($key->fresh()->key)->toBe('new_key');
});

it('updates source phrase and returns json when requested', function () {
    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->putJson(route('ltu.source.update', $key), [
            'value' => 'JSON updated value',
        ])
        ->assertOk()
        ->assertJson(['message' => 'Source phrase updated.']);
});

it('destroys a single translation key', function () {
    $key = TranslationKey::factory()->create();
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.source.destroy', $key))
        ->assertRedirect(route('ltu.source.index'));

    expect(TranslationKey::query()->find($key->id))->toBeNull();
    expect(Translation::query()->where('translation_key_id', $key->id)->count())->toBe(0);
});

it('forbids destroy for viewers', function () {
    $viewer = Contributor::factory()->viewer()->create();
    $key = TranslationKey::factory()->create();

    $this->actingAs($viewer, 'translations')
        ->delete(route('ltu.source.destroy', $key))
        ->assertForbidden();
});

it('bulk deletes keys via destroyBulk', function () {
    $keys = TranslationKey::factory()->count(3)->create();
    $ids = $keys->pluck('id')->all();

    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.source.destroy-bulk'), [
            'ids' => $ids,
        ])
        ->assertRedirect(route('ltu.source.index'));

    expect(TranslationKey::query()->whereIn('id', $ids)->count())->toBe(0);
});

it('forbids bulk delete for translators', function () {
    $translator = Contributor::factory()->translator()->create();
    $key = TranslationKey::factory()->create();

    $this->actingAs($translator, 'translations')
        ->delete(route('ltu.source.destroy-bulk'), [
            'ids' => [$key->id],
        ])
        ->assertForbidden();
});

it('includes previous and next key in show data', function () {
    $group = Group::factory()->create(['name' => 'app']);
    $key1 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'a_first']);
    $key2 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'b_middle']);
    $key3 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'c_last']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.show', $key2))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('previousKey', $key1->id)
            ->where('nextKey', $key3->id)
        );
});
