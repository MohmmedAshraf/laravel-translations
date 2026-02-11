<?php

use Illuminate\Support\Facades\Hash;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

beforeEach(function () {
    useContributorAuth();
});

it('creates a contributor via command', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Test User',
        '--email' => 'test@example.com',
    ])->assertSuccessful();

    $this->assertDatabaseHas('ltu_contributors', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

it('defaults to translator role with no-interaction', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Default Role',
        '--email' => 'default@example.com',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'default@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Translator);
});

it('assigns specified role', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Admin User',
        '--email' => 'admin@example.com',
        '--role' => 'admin',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'admin@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Admin);
});

it('defaults password to "password" with no-interaction', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Pass Test',
        '--email' => 'pass@example.com',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'pass@example.com')->first();
    expect(Hash::check('password', $contributor->password))->toBeTrue();
});
