<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
});

it('shares auth user data via inertia', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('auth.user')
            ->has('auth.role')
        );
});

it('shares null user when unauthenticated', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('auth.role', 'owner')
        );
});

it('shares flash messages', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('flash')
        );
});

it('shares navigation items', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('translationsNav')
        );
});

it('shares contributor mode flag', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('isContributorMode', true)
        );
});

it('shares environment info', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('environment')
        );
});
