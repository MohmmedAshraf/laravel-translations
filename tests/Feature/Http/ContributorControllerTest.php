<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->source()->create();
    $this->arabic = Language::factory()->create(['code' => 'ar', 'name' => 'Arabic']);
    $this->french = Language::factory()->create(['code' => 'fr', 'name' => 'French']);
});

it('lists all contributors', function () {
    $owner = Contributor::factory()->owner()->create();
    Contributor::factory()->translator()->count(3)->create();

    $this->actingAs($owner, 'translations')
        ->get(route('ltu.contributors.index'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('translations/contributors/index')
            ->has('data.data', 3)
            ->has('roles')
            ->has('languages')
        );
});

it('denies translator from contributors page', function () {
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($translator, 'translations')
        ->get(route('ltu.contributors.index'))
        ->assertForbidden();
});

it('searches contributors by name', function () {
    $owner = Contributor::factory()->owner()->create();
    Contributor::factory()->create(['name' => 'Ahmed Khalid']);
    Contributor::factory()->create(['name' => 'Sarah Chen']);

    $response = $this->actingAs($owner, 'translations')
        ->get(route('ltu.contributors.index', ['search' => 'Ahmed']));

    $response->assertSuccessful();

    $data = collect($response->inertiaProps('data.data'));
    expect($data)->not->toBeEmpty();

    $names = $data->pluck('name')->all();
    expect($names)->toContain('Ahmed Khalid');
});

it('creates contributor with invite', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.store'), [
            'email' => 'new@example.com',
            'name' => 'New Contributor',
            'role' => 'translator',
            'language_ids' => [$this->arabic->id],
        ])
        ->assertRedirect();

    $contributor = Contributor::query()->where('email', 'new@example.com')->first();

    expect($contributor)->not->toBeNull();
    expect($contributor->role)->toBe(ContributorRole::Translator);
    expect($contributor->password)->toBeNull();
    expect($contributor->invite_token)->not->toBeNull();
    expect($contributor->languages)->toHaveCount(1);
});

it('updates contributor role', function () {
    $owner = Contributor::factory()->owner()->create();
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($owner, 'translations')
        ->put(route('ltu.contributors.update', $translator), [
            'role' => 'reviewer',
            'language_ids' => [],
        ])
        ->assertRedirect();

    expect($translator->fresh()->role)->toBe(ContributorRole::Reviewer);
});

it('updates contributor language assignments', function () {
    $owner = Contributor::factory()->owner()->create();
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($owner, 'translations')
        ->put(route('ltu.contributors.update', $translator), [
            'role' => 'translator',
            'language_ids' => [$this->arabic->id, $this->french->id],
        ])
        ->assertRedirect();

    expect($translator->fresh()->languages)->toHaveCount(2);
});

it('prevents removing last owner', function () {
    $owner = Contributor::factory()->owner()->create();

    $this->actingAs($owner, 'translations')
        ->put(route('ltu.contributors.update', $owner), [
            'role' => 'admin',
            'language_ids' => [],
        ])
        ->assertSessionHasErrors('role');
});

it('prevents self demotion', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->put(route('ltu.contributors.update', $admin), [
            'role' => 'translator',
            'language_ids' => [],
        ])
        ->assertSessionHasErrors('role');
});

it('prevents admin from promoting to owner', function () {
    $admin = Contributor::factory()->admin()->create();
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($admin, 'translations')
        ->put(route('ltu.contributors.update', $translator), [
            'role' => 'owner',
            'language_ids' => [],
        ])
        ->assertSessionHasErrors('role');
});

it('deletes contributor', function () {
    $owner = Contributor::factory()->owner()->create();
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($owner, 'translations')
        ->delete(route('ltu.contributors.destroy', $translator))
        ->assertRedirect();

    $this->assertDatabaseMissing('ltu_contributors', ['id' => $translator->id]);
});

it('shows invited status for pending invites', function () {
    $owner = Contributor::factory()->owner()->create();
    $invitedContributor = Contributor::factory()->invited()->create();

    $response = $this->actingAs($owner, 'translations')
        ->get(route('ltu.contributors.index'));

    $response->assertSuccessful();

    $invited = collect($response->inertiaProps('data.data'))
        ->firstWhere('id', $invitedContributor->id);

    expect($invited['status'])->toBe('invited');
});

it('shows active status for accepted contributors', function () {
    $owner = Contributor::factory()->owner()->create();
    $active = Contributor::factory()->create(['invite_token' => null]);

    $response = $this->actingAs($owner, 'translations')
        ->get(route('ltu.contributors.index'));

    $response->assertSuccessful();

    $found = collect($response->inertiaProps('data.data'))
        ->firstWhere('id', $active->id);

    expect($found['status'])->toBe('active');
});

it('shows inactive status for deactivated contributors', function () {
    $owner = Contributor::factory()->owner()->create();
    $inactive = Contributor::factory()->create(['is_active' => false]);

    $response = $this->actingAs($owner, 'translations')
        ->get(route('ltu.contributors.index'));

    $response->assertSuccessful();

    $found = collect($response->inertiaProps('data.data'))
        ->firstWhere('id', $inactive->id);

    expect($found['status'])->toBe('inactive');
});

it('denies viewer from contributors page', function () {
    $viewer = Contributor::factory()->viewer()->create();

    $this->actingAs($viewer, 'translations')
        ->get(route('ltu.contributors.index'))
        ->assertForbidden();
});

it('validates required fields when creating contributor', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'role']);
});

it('validates email format when creating contributor', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.store'), [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'role' => 'translator',
        ])
        ->assertSessionHasErrors(['email']);
});

it('validates duplicate email when creating contributor', function () {
    $admin = Contributor::factory()->admin()->create();
    $existing = Contributor::factory()->create(['email' => 'existing@example.com']);

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.store'), [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'role' => 'translator',
        ])
        ->assertSessionHasErrors(['email']);
});

it('prevents deactivating the last owner', function () {
    $owner = Contributor::factory()->owner()->create();

    $this->actingAs($owner, 'translations')
        ->post(route('ltu.contributors.toggle-active', $owner))
        ->assertSessionHas('error');

    expect($owner->fresh()->is_active)->toBeTrue();
});

it('re-activates a deactivated contributor', function () {
    $admin = Contributor::factory()->admin()->create();
    $inactive = Contributor::factory()->create(['is_active' => false]);

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.toggle-active', $inactive))
        ->assertRedirect();

    expect($inactive->fresh()->is_active)->toBeTrue();
});

it('validates role when creating contributor', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'invalid-role',
        ])
        ->assertSessionHasErrors(['role']);
});
