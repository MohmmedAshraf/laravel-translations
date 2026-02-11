<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

beforeEach(function () {
    useContributorAuth();
});

it('creates contributor with owner role via option', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Owner User',
        '--email' => 'owner@example.com',
        '--role' => 'owner',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'owner@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Owner);
});

it('creates contributor with reviewer role', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Reviewer',
        '--email' => 'reviewer@example.com',
        '--role' => 'reviewer',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'reviewer@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Reviewer);
});

it('creates contributor with viewer role', function () {
    $this->artisan('translations:create-user', [
        '--no-interaction' => true,
        '--name' => 'Viewer',
        '--email' => 'viewer@example.com',
        '--role' => 'viewer',
    ])->assertSuccessful();

    $contributor = Contributor::query()->where('email', 'viewer@example.com')->first();
    expect($contributor->role)->toBe(ContributorRole::Viewer);
});
