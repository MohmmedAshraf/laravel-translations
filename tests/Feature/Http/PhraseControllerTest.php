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
    $this->language = Language::factory()->create(['code' => 'fr', 'is_source' => false, 'active' => true]);
});

it('shows the phrases index for a non-source language', function () {
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'auth.login']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->language->id,
        'value' => 'Se connecter',
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/phrases/index')
            ->where('data.total', 1)
            ->has('tableConfig')
            ->has('language')
            ->has('sourceLanguage')
        );
});

it('returns 404 for source language', function () {
    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->sourceLanguage))
        ->assertNotFound();
});

it('includes tableConfig with columns and filters', function () {
    TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('tableConfig.columns', 5)
            ->where('tableConfig.columns.0.id', 'status')
            ->where('tableConfig.columns.1.id', 'key')
            ->where('tableConfig.columns.2.id', 'group_name')
            ->where('tableConfig.columns.3.id', 'source_value')
            ->where('tableConfig.columns.4.id', 'translation_value')
            ->has('tableConfig.filters', 3)
            ->where('tableConfig.filters.0.key', 'status')
            ->where('tableConfig.filters.1.key', 'group_id')
            ->where('tableConfig.filters.2.key', 'missing_params')
        );
});

it('includes source translations in mapped data', function () {
    $key = TranslationKey::factory()->create(['key' => 'auth.login']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
        'value' => 'Log in',
    ]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->language->id,
        'value' => 'Se connecter',
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.source_value', 'Log in')
            ->where('data.data.0.translation_value', 'Se connecter')
            ->where('data.data.0.status', 'translated')
        );
});

it('filters by status', function () {
    $group = Group::factory()->create(['name' => 'app']);
    $key1 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'translated_key']);
    $key2 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'untranslated_key']);
    Translation::factory()->create([
        'translation_key_id' => $key1->id,
        'language_id' => $this->language->id,
        'status' => 'translated',
    ]);
    Translation::factory()->untranslated()->create([
        'translation_key_id' => $key2->id,
        'language_id' => $this->language->id,
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', ['language' => $this->language, 'filter' => ['status' => 'translated']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.key', 'app.translated_key')
        );
});

it('filters by group', function () {
    $authGroup = Group::factory()->create(['name' => 'auth']);
    $dashGroup = Group::factory()->create(['name' => 'dashboard']);
    TranslationKey::factory()->create(['group_id' => $authGroup->id, 'key' => 'login']);
    TranslationKey::factory()->create(['group_id' => $dashGroup->id, 'key' => 'welcome']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', ['language' => $this->language, 'filter' => ['group_id' => $authGroup->id]]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.key', 'auth.login')
        );
});

it('default sort is by key', function () {
    $group = Group::factory()->create(['name' => 'app']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'z_last']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'a_first']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.key', 'app.a_first')
            ->where('data.data.1.key', 'app.z_last')
        );
});

it('rejects translation missing required parameters', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute']]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->language->id,
        'value' => 'Old value with :attribute',
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Missing the param',
        ])
        ->assertSessionHasErrors('value');
});

it('accepts translation containing all required parameters', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute', ':max']]);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Le champ :attribute ne doit pas dépasser :max.',
        ])
        ->assertSessionHasNoErrors();
});

it('allows null value regardless of parameters', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute']]);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => null,
        ])
        ->assertSessionHasNoErrors();
});

it('rejects plural translation with wrong number of variants', function () {
    $key = TranslationKey::factory()->create(['is_plural' => true, 'parameters' => [':count']]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
        'value' => '{0} No items|{1} :count item|[2,*] :count items',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => ':count élément|:count éléments',
        ])
        ->assertSessionHasErrors('value');
});

it('accepts plural translation with correct number of variants', function () {
    $key = TranslationKey::factory()->create(['is_plural' => true, 'parameters' => [':count']]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
        'value' => '{0} No items|{1} :count item|[2,*] :count items',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => '{0} Aucun élément|{1} :count élément|[2,*] :count éléments',
        ])
        ->assertSessionHasNoErrors();
});

it('filters by missing params', function () {
    $group = Group::factory()->create(['name' => 'validation']);
    $keyWithParams = TranslationKey::factory()->create([
        'group_id' => $group->id,
        'key' => 'required',
        'parameters' => [':attribute'],
    ]);
    $keyWithoutParams = TranslationKey::factory()->create([
        'group_id' => $group->id,
        'key' => 'accepted',
        'parameters' => null,
    ]);
    $keyParamsComplete = TranslationKey::factory()->create([
        'group_id' => $group->id,
        'key' => 'min',
        'parameters' => [':attribute', ':min'],
    ]);

    Translation::factory()->create([
        'translation_key_id' => $keyWithParams->id,
        'language_id' => $this->language->id,
        'value' => 'Missing the param completely',
        'status' => 'translated',
    ]);
    Translation::factory()->create([
        'translation_key_id' => $keyWithoutParams->id,
        'language_id' => $this->language->id,
        'value' => 'No params needed',
        'status' => 'translated',
    ]);
    Translation::factory()->create([
        'translation_key_id' => $keyParamsComplete->id,
        'language_id' => $this->language->id,
        'value' => ':attribute must be at least :min',
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', ['language' => $this->language, 'filter' => ['missing_params' => '1']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.key', 'validation.required')
        );
});

it('includes missing_params in tableConfig filters', function () {
    TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('tableConfig.filters', 3)
            ->where('tableConfig.filters.2.key', 'missing_params')
        );
});

it('includes flattened group_name in row data', function () {
    $group = Group::factory()->create(['name' => 'messages']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'messages.hello']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.group_name', 'messages')
        );
});

it('passes translationIsEmpty as true when translation has no value', function () {
    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', ['language' => $this->language, 'translationKey' => $key]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('translationIsEmpty', true)
        );
});

it('passes translationIsEmpty as false when translation has a value', function () {
    $key = TranslationKey::factory()->create();
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->language->id,
        'value' => 'Some translation',
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', ['language' => $this->language, 'translationKey' => $key]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('translationIsEmpty', false)
        );
});
