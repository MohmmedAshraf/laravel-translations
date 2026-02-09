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

it('shows the source language index with table data', function () {
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'auth.login']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
        'value' => 'Log in',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/source-language/index')
            ->where('data.total', 1)
            ->has('tableConfig')
            ->has('groups')
            ->has('sourceLanguage')
        );
});

it('includes tableConfig with columns, filters, and sortableColumns', function () {
    TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('tableConfig.columns', 3)
            ->where('tableConfig.columns.0.id', 'key')
            ->where('tableConfig.columns.1.id', 'group_name')
            ->where('tableConfig.columns.2.id', 'source_value')
            ->has('tableConfig.filters', 1)
            ->where('tableConfig.filters.0.key', 'group_id')
            ->has('tableConfig.sortableColumns')
        );
});

it('searches keys by key name', function () {
    $group = Group::factory()->create(['name' => 'auth']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'login']);
    TranslationKey::factory()->create(['key' => 'welcome']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index', ['filter' => ['search' => 'login']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.key', 'auth.login')
        );
});

it('filters by group', function () {
    $authGroup = Group::factory()->create(['name' => 'auth']);
    $dashGroup = Group::factory()->create(['name' => 'dashboard']);
    TranslationKey::factory()->create(['group_id' => $authGroup->id, 'key' => 'login']);
    TranslationKey::factory()->create(['group_id' => $dashGroup->id, 'key' => 'welcome']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index', ['filter' => ['group_id' => $authGroup->id]]))
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
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.key', 'app.a_first')
            ->where('data.data.1.key', 'app.z_last')
        );
});

it('includes bulk actions in tableConfig', function () {
    TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('tableConfig.bulkActions', 1)
            ->where('tableConfig.bulkActions.0.name', 'delete')
            ->where('tableConfig.bulkActions.0.variant', 'destructive')
        );
});

it('bulk deletes selected keys', function () {
    $key1 = TranslationKey::factory()->create();
    $key2 = TranslationKey::factory()->create();
    Translation::factory()->create(['translation_key_id' => $key1->id, 'language_id' => $this->sourceLanguage->id]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.source.bulk-action', 'delete'), [
            'ids' => [$key1->id, $key2->id],
        ])
        ->assertRedirect(route('ltu.source.index'));

    expect(TranslationKey::query()->find($key1->id))->toBeNull();
    expect(TranslationKey::query()->find($key2->id))->toBeNull();
    expect(Translation::query()->where('translation_key_id', $key1->id)->count())->toBe(0);
});

it('includes groups for AddKeyDialog', function () {
    Group::factory()->count(3)->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('groups', 3)
        );
});

it('maps source_value and group_name in data', function () {
    $group = Group::factory()->create(['name' => 'messages']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'messages.hello']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->sourceLanguage->id,
        'value' => 'Hello World',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.source_value', 'Hello World')
            ->where('data.data.0.group_name', 'messages')
        );
});
