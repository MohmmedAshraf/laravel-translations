<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
});

it('allows admin to access admin-only routes', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk();
});

it('allows owner to access admin routes', function () {
    $owner = Contributor::factory()->owner()->create();

    $this->actingAs($owner, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk();
});

it('allows translator to access translator routes', function () {
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($translator, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk();
});

it('blocks viewer from admin-only routes like import', function () {
    $viewer = Contributor::factory()->viewer()->create();

    $this->actingAs($viewer, 'translations')
        ->post(route('ltu.import'))
        ->assertForbidden();
});

it('blocks translator from admin-only routes like import', function () {
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($translator, 'translations')
        ->post(route('ltu.import'))
        ->assertForbidden();
});
