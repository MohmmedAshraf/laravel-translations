<?php

use Workbench\App\Models\User;

beforeEach(function () {
    config(['translations.auth.driver' => 'users']);
});

it('redirects unauthenticated users to login', function () {
    $this->get(route('ltu.languages.index'))
        ->assertRedirect(config('translations.auth.login_url', '/login'));
});

it('allows authenticated app users to access translations', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ltu.languages.index'))
        ->assertOk();
});

it('uses default guard for app user authentication', function () {
    $auth = app('translations.auth');

    expect($auth->guardName())->toBe('web');
});

it('is not in contributor mode', function () {
    $auth = app('translations.auth');

    expect($auth->isContributorMode())->toBeFalse();
});

it('checks app user authentication via translations auth service', function () {
    $auth = app('translations.auth');

    expect($auth->check())->toBeFalse();

    $user = User::factory()->create();
    $this->actingAs($user);

    expect($auth->check())->toBeTrue();
});
