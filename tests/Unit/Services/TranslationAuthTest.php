<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Services\TranslationAuth;

beforeEach(function () {
    useContributorAuth();
});

it('returns null when no user is authenticated', function () {
    $auth = app(TranslationAuth::class);

    expect($auth->user())->toBeNull()
        ->and($auth->id())->toBeNull()
        ->and($auth->displayName())->toBeNull()
        ->and($auth->role())->toBeNull();
});

it('returns user details when authenticated', function () {
    $contributor = Contributor::factory()->owner()->create([
        'name' => 'Test',
        'email' => 'test@example.com',
    ]);
    auth()->guard('translations')->login($contributor);

    $auth = app(TranslationAuth::class);

    expect($auth->user())->not->toBeNull()
        ->and($auth->id())->toBe((string) $contributor->id)
        ->and($auth->displayName())->toBe('Test')
        ->and($auth->role()->value)->toBe('owner')
        ->and($auth->check())->toBeTrue();
});

it('returns correct guard name for contributor mode', function () {
    $auth = app(TranslationAuth::class);

    expect($auth->isContributorMode())->toBeTrue()
        ->and($auth->guardName())->toBe('translations');
});

it('returns correct guard name for users mode', function () {
    config(['translations.auth.driver' => 'users']);
    config(['translations.auth.guard' => 'web']);

    $auth = app(TranslationAuth::class);

    expect($auth->isContributorMode())->toBeFalse()
        ->and($auth->guardName())->toBe('web');
});

it('returns assigned language ids for contributor', function () {
    $contributor = Contributor::factory()->translator()->create();
    $language = Language::factory()->create();
    $contributor->languages()->attach($language);
    auth()->guard('translations')->login($contributor);

    $auth = app(TranslationAuth::class);

    expect($auth->assignedLanguageIds())->toContain($language->id);
});

it('returns empty collection for non-contributor user', function () {
    config(['translations.auth.driver' => 'users']);

    $auth = app(TranslationAuth::class);

    expect($auth->assignedLanguageIds())->toBeEmpty();
});

it('allows admin to access any language', function () {
    $admin = Contributor::factory()->admin()->create();
    auth()->guard('translations')->login($admin);
    $language = Language::factory()->create();

    $auth = app(TranslationAuth::class);

    expect($auth->isAssignedToLanguage($language->id))->toBeTrue();
});

it('returns false for unassigned language for translator', function () {
    $translator = Contributor::factory()->translator()->create();
    auth()->guard('translations')->login($translator);
    $language = Language::factory()->create();

    $auth = app(TranslationAuth::class);

    expect($auth->isAssignedToLanguage($language->id))->toBeFalse();
});

it('returns true for assigned language for translator', function () {
    $translator = Contributor::factory()->translator()->create();
    $language = Language::factory()->create();
    $translator->languages()->attach($language);
    auth()->guard('translations')->login($translator);

    $auth = app(TranslationAuth::class);

    expect($auth->isAssignedToLanguage($language->id))->toBeTrue();
});

it('returns false when not authenticated', function () {
    $auth = app(TranslationAuth::class);

    expect($auth->isAssignedToLanguage(1))->toBeFalse()
        ->and($auth->check())->toBeFalse();
});

it('caches assigned language ids', function () {
    $translator = Contributor::factory()->translator()->create();
    $language = Language::factory()->create();
    $translator->languages()->attach($language);
    auth()->guard('translations')->login($translator);

    $auth = app(TranslationAuth::class);

    $first = $auth->assignedLanguageIds();
    $second = $auth->assignedLanguageIds();

    expect($first)->toBe($second);
});
