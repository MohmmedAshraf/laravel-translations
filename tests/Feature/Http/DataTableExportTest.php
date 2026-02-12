<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
    $this->sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
});

it('handles per_page with valid allowed value', function () {
    $group = Group::factory()->create(['name' => 'auth']);
    TranslationKey::factory()->count(30)->create(['group_id' => $group->id]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index', ['per_page' => 25]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.per_page', 25)
        );
});

it('defaults per_page for invalid values', function () {
    $group = Group::factory()->create(['name' => 'auth']);
    TranslationKey::factory()->count(20)->create(['group_id' => $group->id]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index', ['per_page' => 13]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.per_page', 15)
        );
});

it('sorts by key descending', function () {
    $group = Group::factory()->create(['name' => 'app']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'a_first']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'z_last']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index', ['sort' => '-key']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.data.0.key', 'app.z_last')
            ->where('data.data.1.key', 'app.a_first')
        );
});

it('handles select_all in bulk action', function () {
    $keys = TranslationKey::factory()->count(3)->create();

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.source.bulk-action', 'delete'), [
            'select_all' => true,
        ])
        ->assertRedirect(route('ltu.source.index'));

    expect(TranslationKey::query()->count())->toBe(0);
});

it('returns 404 for unknown bulk action', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.source.bulk-action', 'nonexistent'), [
            'ids' => [],
        ])
        ->assertNotFound();
});
