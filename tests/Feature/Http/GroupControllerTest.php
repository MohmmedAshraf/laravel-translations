<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
});

it('shows the groups index page', function () {
    Group::factory()->count(3)->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/groups/index')
            ->has('groups', 3)
        );
});

it('creates a new group', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.groups.store'), [
            'name' => 'notifications',
        ])
        ->assertRedirect(route('ltu.groups.index'));

    expect(Group::query()->where('name', 'notifications')->exists())->toBeTrue();
});

it('validates group creation', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.groups.store'), [
            'name' => '',
        ])
        ->assertSessionHasErrors('name');
});

it('updates a group', function () {
    $group = Group::factory()->create(['name' => 'old_name']);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.groups.update', $group), [
            'name' => 'new_name',
        ])
        ->assertRedirect(route('ltu.groups.index'));

    $group->refresh();
    expect($group->name)->toBe('new_name');
});

it('deletes a group', function () {
    $group = Group::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.groups.destroy', $group))
        ->assertRedirect(route('ltu.groups.index'));

    expect(Group::query()->find($group->id))->toBeNull();
});

it('includes translation key count', function () {
    $group = Group::factory()->create();
    TranslationKey::factory()->count(5)->create(['group_id' => $group->id]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('groups.0.translation_keys_count', 5)
        );
});
