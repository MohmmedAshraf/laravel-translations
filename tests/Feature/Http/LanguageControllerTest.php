<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
});

it('shows the languages index page with only active languages', function () {
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => true]);
    Language::factory()->create(['code' => 'de', 'active' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/languages')
            ->where('data.total', 2)
            ->has('tableConfig')
            ->has('availableLanguages', 1)
            ->has('totalKeys')
        );
});

it('shows source language first in the list', function () {
    Language::factory()->create(['code' => 'fr', 'name' => 'French', 'active' => true]);
    Language::factory()->create(['code' => 'en', 'name' => 'English', 'is_source' => true, 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.is_source', true)
        );
});

it('activates an existing language from the catalog', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$language->id],
        ])
        ->assertRedirect(route('ltu.languages.index'));

    $language->refresh();
    expect($language->active)->toBeTrue();
});

it('activates multiple languages at once', function () {
    $fr = Language::factory()->create(['code' => 'fr', 'active' => false]);
    $de = Language::factory()->create(['code' => 'de', 'active' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$fr->id, $de->id],
        ])
        ->assertRedirect(route('ltu.languages.index'))
        ->assertSessionHas('success');

    expect($fr->refresh()->active)->toBeTrue();
    expect($de->refresh()->active)->toBeTrue();
});

it('validates language activation requires existing languages', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [999],
        ])
        ->assertSessionHasErrors('language_ids.0');
});

it('skips already active languages silently', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$language->id],
        ])
        ->assertRedirect(route('ltu.languages.index'))
        ->assertSessionHas('info');
});

it('deactivates a non-source language', function () {
    $language = Language::factory()->create(['code' => 'fr', 'is_source' => false, 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.languages.destroy', $language))
        ->assertRedirect(route('ltu.languages.index'));

    $language->refresh();
    expect($language->active)->toBeFalse();
    expect(Language::query()->find($language->id))->not->toBeNull();
});

it('prevents deactivation of source language', function () {
    $language = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.languages.destroy', $language))
        ->assertRedirect(route('ltu.languages.index'))
        ->assertSessionHas('error');

    $language->refresh();
    expect($language->active)->toBeTrue();
});

it('replicates keys when activating a language', function () {
    TranslationKey::factory()->count(3)->create();
    $language = Language::factory()->create(['code' => 'de', 'active' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$language->id],
        ]);

    $language->refresh();
    expect($language->translations()->count())->toBe(3);
});

it('passes available languages for the add dialog', function () {
    Language::factory()->create(['code' => 'en', 'active' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => false]);
    Language::factory()->create(['code' => 'de', 'active' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('availableLanguages', 2)
        );
});

it('filters languages by completed status', function () {
    $keys = TranslationKey::factory()->count(2)->create();
    $completed = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $inProgress = Language::factory()->create(['code' => 'de', 'active' => true]);

    foreach ($keys as $key) {
        Translation::factory()->create([
            'translation_key_id' => $key->id,
            'language_id' => $completed->id,
            'status' => 'translated',
        ]);
        Translation::factory()->create([
            'translation_key_id' => $key->id,
            'language_id' => $inProgress->id,
            'status' => 'untranslated',
        ]);
    }

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'completed']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.code', 'fr')
        );
});

it('filters languages by needs_review status', function () {
    $key = TranslationKey::factory()->create();
    $reviewLang = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $cleanLang = Language::factory()->create(['code' => 'de', 'active' => true]);

    Translation::factory()->needsReview()->create([
        'translation_key_id' => $key->id,
        'language_id' => $reviewLang->id,
    ]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $cleanLang->id,
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'needs_review']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.code', 'fr')
        );
});

it('includes filter config in table config', function () {
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('tableConfig.filters', 1)
            ->where('tableConfig.filters.0.key', 'status')
        );
});

it('bulk deletes non-source languages and their translations', function () {
    $source = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $fr = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $de = Language::factory()->create(['code' => 'de', 'active' => true]);

    $key = TranslationKey::factory()->create();
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $fr->id]);
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $de->id]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.bulk-action', 'delete'), [
            'ids' => [$fr->id, $de->id],
        ])
        ->assertRedirect(route('ltu.languages.index'));

    expect(Language::query()->find($fr->id))->toBeNull();
    expect(Language::query()->find($de->id))->toBeNull();
    expect(Translation::query()->where('language_id', $fr->id)->count())->toBe(0);
    expect(Translation::query()->where('language_id', $de->id)->count())->toBe(0);
});

it('skips source language in bulk delete', function () {
    $source = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $fr = Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.bulk-action', 'delete'), [
            'ids' => [$source->id, $fr->id],
        ])
        ->assertRedirect(route('ltu.languages.index'))
        ->assertSessionHas('warning');

    expect(Language::query()->find($source->id))->not->toBeNull();
    expect(Language::query()->find($fr->id))->toBeNull();
});

it('includes bulk actions in table config', function () {
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('tableConfig.bulkActions', 1)
            ->where('tableConfig.bulkActions.0.name', 'delete')
            ->where('tableConfig.bulkActions.0.variant', 'destructive')
        );
});

it('includes progress in the language data', function () {
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('data.data.0.progress')
        );
});

it('re-activates a language with existing translations without duplicating them', function () {
    $keys = TranslationKey::factory()->count(2)->create();
    $language = Language::factory()->create(['code' => 'fr', 'active' => false]);

    foreach ($keys as $key) {
        Translation::factory()->create([
            'translation_key_id' => $key->id,
            'language_id' => $language->id,
            'value' => 'existing value',
            'status' => 'translated',
        ]);
    }

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$language->id],
        ])
        ->assertRedirect(route('ltu.languages.index'));

    $language->refresh();
    expect($language->active)->toBeTrue();
    expect($language->translations()->count())->toBe(2);
    expect($language->translations()->where('value', 'existing value')->count())->toBe(2);
});

it('creates a custom language', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'tlh',
            'name' => 'Klingon',
            'native_name' => 'tlhIngan Hol',
            'rtl' => false,
        ])
        ->assertRedirect(route('ltu.languages.index'))
        ->assertSessionHas('success');

    $language = Language::query()->where('code', 'tlh')->first();
    expect($language)->not->toBeNull();
    expect($language->name)->toBe('Klingon');
    expect($language->native_name)->toBe('tlhIngan Hol');
    expect($language->active)->toBeTrue();
    expect($language->is_source)->toBeFalse();
});

it('validates custom language code format', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'INVALID!',
            'name' => 'Test',
        ])
        ->assertSessionHasErrors('code');
});

it('validates custom language code uniqueness', function () {
    Language::factory()->create(['code' => 'fr']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'fr',
            'name' => 'French Duplicate',
        ])
        ->assertSessionHasErrors('code');
});

it('validates language_ids is required', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), ['language_ids' => []])
        ->assertSessionHasErrors('language_ids');
});

it('validates custom language name is required', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'tlh',
            'name' => '',
        ])
        ->assertSessionHasErrors('name');
});

it('validates custom language name max length', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'tlh',
            'name' => str_repeat('a', 256),
        ])
        ->assertSessionHasErrors('name');
});

it('replicates keys when creating a custom language', function () {
    TranslationKey::factory()->count(3)->create();

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'tlh',
            'name' => 'Klingon',
        ]);

    $language = Language::query()->where('code', 'tlh')->first();
    expect($language->translations()->count())->toBe(3);
});
