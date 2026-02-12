<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->source()->create();
});

it('prevents admin from storing owner role', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.store'), [
            'name' => 'New Owner',
            'email' => 'owner@example.com',
            'role' => 'owner',
        ])
        ->assertSessionHasErrors('role');
});

it('prevents toggling own active status', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.toggle-active', $admin))
        ->assertSessionHas('error', 'You cannot change your own status.');
});

it('prevents deleting the last active owner', function () {
    $owner = Contributor::factory()->owner()->create();
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->delete(route('ltu.contributors.destroy', $owner))
        ->assertSessionHas('error', 'Cannot delete the last owner.');
});

it('prevents self deletion', function () {
    Contributor::factory()->owner()->create();
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->delete(route('ltu.contributors.destroy', $admin))
        ->assertSessionHas('error', 'You cannot delete yourself.');
});

it('allows owner to promote to owner role', function () {
    $owner = Contributor::factory()->owner()->create();
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($owner, 'translations')
        ->put(route('ltu.contributors.update', $translator), [
            'role' => 'owner',
            'language_ids' => [],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($translator->fresh()->role)->toBe(ContributorRole::Owner);
});

it('detaches languages when deleting contributor', function () {
    $owner = Contributor::factory()->owner()->create();
    $translator = Contributor::factory()->translator()->create();
    $language = Language::factory()->create(['code' => 'fr']);
    $translator->languages()->attach($language);

    $this->actingAs($owner, 'translations')
        ->delete(route('ltu.contributors.destroy', $translator))
        ->assertRedirect();

    $this->assertDatabaseMissing('ltu_contributor_language', [
        'contributor_id' => $translator->id,
    ]);
});

it('deactivates a contributor successfully', function () {
    $owner = Contributor::factory()->owner()->create();
    $active = Contributor::factory()->create(['is_active' => true]);

    $this->actingAs($owner, 'translations')
        ->post(route('ltu.contributors.toggle-active', $active))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($active->fresh()->is_active)->toBeFalse();
});

it('prevents changing role of last owner', function () {
    $owner = Contributor::factory()->owner()->create();
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->put(route('ltu.contributors.update', $owner), [
            'role' => 'admin',
            'language_ids' => [],
        ])
        ->assertSessionHasErrors('role');
});
