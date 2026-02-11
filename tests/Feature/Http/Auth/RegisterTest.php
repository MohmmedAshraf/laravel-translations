<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

beforeEach(function () {
    useContributorAuth();
    config(['translations.registration' => true]);
});

it('shows the register page', function () {
    $this->get(route('ltu.register'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/auth/register')
        );
});

it('registers a new contributor', function () {
    $this->post(route('ltu.register.store'), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('ltu.languages.index'));

    $contributor = Contributor::query()->where('email', 'new@example.com')->first();
    expect($contributor)->not->toBeNull()
        ->and($contributor->role)->toBe(ContributorRole::Translator);

    $this->assertAuthenticatedAs($contributor, 'translations');
});

it('validates required fields', function () {
    $this->post(route('ltu.register.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

it('validates email uniqueness', function () {
    Contributor::factory()->create(['email' => 'existing@example.com']);

    $this->post(route('ltu.register.store'), [
        'name' => 'Test',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');
});

it('returns 404 when registration is disabled', function () {
    config(['translations.registration' => false]);

    $this->get(route('ltu.register'))
        ->assertNotFound();
});
