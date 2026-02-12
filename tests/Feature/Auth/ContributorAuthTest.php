<?php

use Outhebox\Translations\Models\Contributor;

beforeEach(function () {
    useContributorAuth();
});

it('shows the login page', function () {
    $this->get(route('ltu.login'))
        ->assertOk();
});

it('authenticates a contributor with valid credentials', function () {
    $contributor = Contributor::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('ltu.login.store'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ])->assertRedirect(route('ltu.languages.index'));

    $this->assertAuthenticatedAs($contributor, 'translations');
});

it('rejects invalid credentials', function () {
    Contributor::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('ltu.login.store'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest('translations');
});

it('logs out a contributor', function () {
    $contributor = Contributor::factory()->create();

    $this->actingAs($contributor, 'translations')
        ->post(route('ltu.logout'))
        ->assertRedirect(route('ltu.login'));

    $this->assertGuest('translations');
});

it('shows the registration page', function () {
    config(['translations.registration' => true]);

    $this->get(route('ltu.register'))
        ->assertOk();
});

it('registers a new contributor', function () {
    config(['translations.registration' => true]);

    $this->post(route('ltu.register.store'), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('ltu.languages.index'));

    expect(Contributor::query()->where('email', 'new@example.com')->exists())->toBeTrue();
    $this->assertAuthenticated('translations');
});

it('validates registration input', function () {
    config(['translations.registration' => true]);

    $this->post(route('ltu.register.store'), [
        'name' => '',
        'email' => 'invalid',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ])->assertSessionHasErrors(['name', 'email', 'password']);
});
