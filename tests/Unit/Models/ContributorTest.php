<?php

use Outhebox\Translations\Models\Contributor;

it('counts active owners', function () {
    Contributor::factory()->owner()->create();
    Contributor::factory()->owner()->create();
    Contributor::factory()->owner()->create(['is_active' => false]);
    Contributor::factory()->admin()->create();

    expect(Contributor::activeOwnerCount())->toBe(2);
});

it('identifies last active owner', function () {
    $owner = Contributor::factory()->owner()->create();

    expect($owner->isLastActiveOwner())->toBeTrue();
});

it('returns false for isLastActiveOwner when multiple owners exist', function () {
    $owner1 = Contributor::factory()->owner()->create();
    $owner2 = Contributor::factory()->owner()->create();

    expect($owner1->isLastActiveOwner())->toBeFalse()
        ->and($owner2->isLastActiveOwner())->toBeFalse();
});

it('returns false for isLastActiveOwner when not an owner', function () {
    $admin = Contributor::factory()->admin()->create();

    expect($admin->isLastActiveOwner())->toBeFalse();
});

it('returns false for isLastActiveOwner when inactive', function () {
    $owner = Contributor::factory()->owner()->create(['is_active' => false]);

    expect($owner->isLastActiveOwner())->toBeFalse();
});

it('implements TranslatableUser interface', function () {
    $contributor = Contributor::factory()->owner()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($contributor->getTranslationDisplayName())->toBe('Test User')
        ->and($contributor->getTranslationEmail())->toBe('test@example.com')
        ->and($contributor->getTranslationRole())->toBe('owner')
        ->and($contributor->getTranslationId())->toBe((string) $contributor->id);
});

it('manages language relationships', function () {
    $contributor = Contributor::factory()->create();
    $language = Outhebox\Translations\Models\Language::factory()->create();
    $contributor->languages()->attach($language);

    expect($contributor->languages()->count())->toBe(1)
        ->and($contributor->languages->first()->id)->toBe($language->id);
});
