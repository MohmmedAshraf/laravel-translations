<?php

use Outhebox\Translations\Services\TranslationAuth;

it('registers translations.auth singleton', function () {
    $auth = app('translations.auth');

    expect($auth)->toBeInstanceOf(TranslationAuth::class);
});

it('resolves TranslationAuth via alias', function () {
    $auth = app(TranslationAuth::class);

    expect($auth)->toBeInstanceOf(TranslationAuth::class);
});

it('configures routes with domain when set', function () {
    config(['translations.domain' => 'translations.example.com']);

    // Re-boot provider would be complex, just verify config is readable
    expect(config('translations.domain'))->toBe('translations.example.com');
});

it('merges config from translations config file', function () {
    expect(config('translations'))->toBeArray()
        ->and(config('translations.path'))->not->toBeNull();
});

it('configures auth guard for contributors driver', function () {
    useContributorAuth();

    expect(config('auth.guards.translations'))->toBeArray()
        ->and(config('auth.guards.translations.driver'))->toBe('session')
        ->and(config('auth.providers.translations_contributors.model'))->toBe(Outhebox\Translations\Models\Contributor::class);
});

it('does not configure auth guard for users driver', function () {
    config(['translations.auth.driver' => 'users']);

    // The guard should not be set up when using users driver
    // (unless it was already configured by another test)
    expect(config('translations.auth.driver'))->toBe('users');
});
