<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
});

it('filters languages by not_started status', function () {
    $key = TranslationKey::factory()->create();
    $notStarted = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $started = Language::factory()->create(['code' => 'de', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $started->id,
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'not_started']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.code', 'fr')
        );
});

it('filters languages by in_progress status', function () {
    $keys = TranslationKey::factory()->count(2)->create();
    $inProgress = Language::factory()->create(['code' => 'fr', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $keys[0]->id,
        'language_id' => $inProgress->id,
        'status' => 'translated',
    ]);
    Translation::factory()->create([
        'translation_key_id' => $keys[1]->id,
        'language_id' => $inProgress->id,
        'status' => 'untranslated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'in_progress']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.code', 'fr')
        );
});

it('deletes translations when delete_translations flag is set', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $key = TranslationKey::factory()->create();
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'test',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.languages.destroy', $language), ['delete_translations' => true])
        ->assertRedirect();

    expect(Translation::query()->where('language_id', $language->id)->count())->toBe(0);
    expect($language->fresh()->active)->toBeFalse();
});

it('restricts non-admin to assigned languages only', function () {
    $translator = Contributor::factory()->translator()->create();
    $french = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $german = Language::factory()->create(['code' => 'de', 'active' => true]);
    $translator->languages()->attach($french);

    $this->actingAs($translator, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.code', 'fr')
        );
});

it('resolves in_progress language status correctly', function () {
    $keys = TranslationKey::factory()->count(2)->create();
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $keys[0]->id,
        'language_id' => $language->id,
        'status' => 'translated',
    ]);
    Translation::factory()->create([
        'translation_key_id' => $keys[1]->id,
        'language_id' => $language->id,
        'status' => 'untranslated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.status', 'in_progress')
        );
});

it('resolves not_started status when no keys exist', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.status', 'not_started')
        );
});
