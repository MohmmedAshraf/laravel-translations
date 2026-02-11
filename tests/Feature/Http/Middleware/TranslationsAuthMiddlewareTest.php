<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
});

it('redirects unauthenticated users to contributor login', function () {
    $this->get(route('ltu.languages.index'))
        ->assertRedirect(route('ltu.login'));
});

it('redirects unauthenticated users to custom login url in user mode', function () {
    config(['translations.auth.driver' => 'users']);
    config(['translations.auth.login_url' => '/my-login']);

    $this->get(route('ltu.languages.index'))
        ->assertRedirect('/my-login');
});

it('allows authenticated active contributors', function () {
    $contributor = Contributor::factory()->owner()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk();
});

it('blocks inactive contributors with 403', function () {
    $contributor = Contributor::factory()->owner()->inactive()->create();

    $this->actingAs($contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertForbidden();
});
