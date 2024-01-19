<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;

it('login page can be rendered', function () {
    $this->get(route('ltu.login'))
        ->assertStatus(200);
});

it('login request will validate email', function () {
    $response = $this->post(route('ltu.login.attempt'), [
        'email' => 'not-an-email',
        'password' => 'password',
    ])->assertRedirect(route('ltu.login'));

    expect($response->exception)->toBeInstanceOf(ValidationException::class);
});

it('login request will validate password', function () {
    $response = $this->post(route('ltu.login.attempt'), [
        'email' => $this->owner->email,
        'password' => 'what-is-my-password',
    ])->assertSessionHasErrors();

    expect($response->exception)->toBeInstanceOf(ValidationException::class);
});

it('login request will authenticate user', function () {
    $this->withoutExceptionHandling();

    $user = Contributor::factory([
        'role' => RoleEnum::owner,
        'password' => Hash::make('password'),
    ])->create();

    $this->post(route('ltu.login.attempt'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('ltu.translation.index'));
});

it('authenticated users can access dashboard', function () {
    $this->actingAs($this->owner, 'translations')
        ->get(route('ltu.login'))
        ->assertRedirect(route('ltu.translation.index'));
});

it('authenticated users can logout', function () {
    $this->actingAs($this->owner, 'translations')
        ->get(route('ltu.logout'))
        ->assertRedirect(route('ltu.login'));
});
