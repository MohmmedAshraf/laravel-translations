<?php

use Illuminate\Support\Facades\Hash;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
});

// ── CreateUserCommand ──

it('defaults to translator role with no-interaction and no --role', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Default Role',
        '--email' => 'default@example.com',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'default@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Translator);
});

it('defaults password to "password" with no-interaction', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Test',
        '--email' => 'pwd@example.com',
        '--role' => 'admin',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'pwd@example.com')->first();
    expect($contributor)->not->toBeNull()
        ->and(Hash::check('password', $contributor->password))->toBeTrue();
});

it('creates admin role', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Admin',
        '--email' => 'admin@example.com',
        '--role' => 'admin',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'admin@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Admin);
});

// ── InstallCommand ──

it('creates default contributor when using contributors driver', function () {
    config(['translations.auth.driver' => 'contributors']);

    $this->artisan('translations:install', [
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(Contributor::query()->where('email', 'admin@translations.local')->exists())->toBeTrue();
});

it('skips contributor creation with non-contributor driver', function () {
    config(['translations.auth.driver' => 'users']);

    $this->artisan('translations:install', [
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(Contributor::query()->count())->toBe(0);
});

// ── UpdateCommand ──

it('runs update command successfully', function () {
    $this->artisan('translations:update', [
        '--no-interaction' => true,
    ])->assertSuccessful();
});

// ── ExportCommand ──

it('exports translations via command', function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);

    // First import some data
    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('Export completed');
});

it('exports with locale and group filters', function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);

    $this->artisan('translations:import', ['--no-interaction' => true])
        ->assertSuccessful();

    $this->artisan('translations:export', [
        '--no-interaction' => true,
        '--locale' => 'en',
        '--group' => 'auth',
    ])->assertSuccessful();
});

// ── StatusCommand ──

it('runs status command', function () {
    Language::factory()->source()->create();

    $this->artisan('translations:status', [
        '--no-interaction' => true,
    ])->assertSuccessful();
});
