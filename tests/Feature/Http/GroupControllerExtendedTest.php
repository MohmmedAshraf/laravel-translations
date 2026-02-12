<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
});

it('searches groups by name', function () {
    Group::factory()->create(['name' => 'auth']);
    Group::factory()->create(['name' => 'dashboard']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index', ['filter' => ['search' => 'auth']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('groups', 1)
            ->where('groups.0.name', 'auth')
        );
});

it('searches groups by namespace', function () {
    Group::factory()->create(['name' => 'messages', 'namespace' => 'vendor-pkg']);
    Group::factory()->create(['name' => 'errors']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index', ['filter' => ['search' => 'vendor']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('groups', 1)
            ->where('groups.0.namespace', 'vendor-pkg')
        );
});

it('includes filter query in response', function () {
    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index', ['filter' => ['search' => 'test']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('filter.search', 'test')
        );
});

it('escapes special characters in search', function () {
    Group::factory()->create(['name' => 'auth_special']);
    Group::factory()->create(['name' => 'dashboard']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index', ['filter' => ['search' => 'special']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('groups', 1)
        );
});
