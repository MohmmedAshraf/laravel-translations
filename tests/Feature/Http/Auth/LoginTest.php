<?php

use Outhebox\Translations\Models\Contributor;

beforeEach(function () {
    useContributorAuth();
});

it('shows the login page', function () {
    $this->get(route('ltu.login'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/auth/login')
            ->has('canRegister')
        );
});

it('authenticates with valid credentials', function () {
    $contributor = Contributor::factory()->create([
        'password' => 'secret123',
    ]);

    $this->post(route('ltu.login.store'), [
        'email' => $contributor->email,
        'password' => 'secret123',
    ])->assertRedirect();

    $this->assertAuthenticatedAs($contributor, 'translations');
});

it('rejects invalid credentials', function () {
    $contributor = Contributor::factory()->create([
        'password' => 'secret123',
    ]);

    $this->post(route('ltu.login.store'), [
        'email' => $contributor->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest('translations');
});

it('validates email and password are required', function () {
    $this->post(route('ltu.login.store'), [])
        ->assertSessionHasErrors(['email', 'password']);
});

it('validates email format', function () {
    $this->post(route('ltu.login.store'), [
        'email' => 'not-an-email',
        'password' => 'password',
    ])->assertSessionHasErrors('email');
});

it('rate limits login attempts', function () {
    $contributor = Contributor::factory()->create([
        'password' => 'secret123',
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('ltu.login.store'), [
            'email' => $contributor->email,
            'password' => 'wrong-password',
        ]);
    }

    $this->post(route('ltu.login.store'), [
        'email' => $contributor->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');
});

it('logs out authenticated user', function () {
    $contributor = Contributor::factory()->create();

    $this->actingAs($contributor, 'translations')
        ->post(route('ltu.logout'))
        ->assertRedirect(route('ltu.login'));

    $this->assertGuest('translations');
});
