<?php

use Illuminate\Support\Facades\Auth;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Services\TranslationAuth;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->auth = app('translations.auth');
});

it('is registered as a singleton', function () {
    expect($this->auth)->toBeInstanceOf(TranslationAuth::class);
    expect(app('translations.auth'))->toBe($this->auth);
});

it('detects contributor mode from config', function () {
    config(['translations.auth.driver' => 'contributors']);
    expect($this->auth->isContributorMode())->toBeTrue();

    config(['translations.auth.driver' => 'users']);
    expect($this->auth->isContributorMode())->toBeFalse();
});

it('returns translations guard name in contributor mode', function () {
    config(['translations.auth.driver' => 'contributors']);
    expect($this->auth->guardName())->toBe('translations');
});

it('returns default guard name in user mode', function () {
    config(['translations.auth.driver' => 'users']);
    expect($this->auth->guardName())->toBe('web');
});

it('returns null when not authenticated', function () {
    expect($this->auth->user())->toBeNull();
    expect($this->auth->id())->toBeNull();
    expect($this->auth->displayName())->toBeNull();
    expect($this->auth->role())->toBeNull();
});

it('returns false for check when not authenticated', function () {
    expect($this->auth->check())->toBeFalse();
});

it('returns contributor user in contributor mode', function () {
    useContributorAuth();

    $contributor = Contributor::factory()->admin()->create();
    Auth::guard('translations')->login($contributor);

    expect($this->auth->check())->toBeTrue();
    expect($this->auth->user())->toBeInstanceOf(Contributor::class);
    expect($this->auth->id())->toBe((string) $contributor->id);
});

it('returns display name from contributor', function () {
    useContributorAuth();

    $contributor = Contributor::factory()->create(['name' => 'Jane Doe']);
    Auth::guard('translations')->login($contributor);

    expect($this->auth->displayName())->toBe('Jane Doe');
});

it('returns role from contributor', function () {
    useContributorAuth();

    $contributor = Contributor::factory()->admin()->create();
    Auth::guard('translations')->login($contributor);

    expect($this->auth->role())->toBe(ContributorRole::Admin);
});

it('checks authentication in user mode', function () {
    config(['translations.auth.driver' => 'users']);

    expect($this->auth->check())->toBeFalse();

    $user = User::factory()->create();
    Auth::login($user);

    expect($this->auth->check())->toBeTrue();
    expect($this->auth->user())->toBeNull();
});
