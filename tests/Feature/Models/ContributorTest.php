<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

it('creates a contributor with a ULID primary key', function () {
    $contributor = Contributor::factory()->create();

    expect($contributor->id)->toBeString();
    expect(strlen($contributor->id))->toBe(26);
});

it('hashes the password on creation', function () {
    $contributor = Contributor::factory()->create(['password' => 'secret123']);

    expect($contributor->password)->not->toBe('secret123');
    expect(password_verify('secret123', $contributor->password))->toBeTrue();
});

it('casts role to ContributorRole enum', function () {
    $contributor = Contributor::factory()->owner()->create();

    expect($contributor->role)->toBe(ContributorRole::Owner);
});

it('implements TranslatableUser interface', function () {
    $contributor = Contributor::factory()->create(['name' => 'John Doe']);

    expect($contributor->getTranslationDisplayName())->toBe('John Doe');
    expect($contributor->getTranslationRole())->toBeString();
    expect($contributor->getTranslationEmail())->toBe($contributor->email);
    expect($contributor->getTranslationId())->toBe((string) $contributor->id);
});

it('has is_active field defaulting to true', function () {
    $contributor = Contributor::factory()->create();

    expect($contributor->is_active)->toBeTrue();
});

it('can be deactivated', function () {
    $contributor = Contributor::factory()->inactive()->create();

    expect($contributor->is_active)->toBeFalse();
});

it('has languages relationship', function () {
    $contributor = Contributor::factory()->create();
    $en = Language::factory()->create(['code' => 'en']);
    $fr = Language::factory()->create(['code' => 'fr']);

    $contributor->languages()->attach([$en->id, $fr->id]);

    expect($contributor->languages)->toHaveCount(2);
});
