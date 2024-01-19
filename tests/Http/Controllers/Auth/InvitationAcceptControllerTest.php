<?php

use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Models\Invite;

test('invitation accept page can be rendered', function () {
    $invite = Invite::factory()->create();

    $this->get(route('ltu.invitation.accept', ['token' => $invite->token]))
        ->assertStatus(200);
});

test('invitation accept page returns 404 if token is invalid', function () {
    $this->get(route('ltu.invitation.accept', ['token' => 'invalid-token']))
        ->assertStatus(404);
});

test('invitation accept store will create contributor and login', function () {
    $invite = Invite::factory()->create();

    $translator = Contributor::factory()->make([
        'email' => $invite->email,
        'role' => RoleEnum::translator,
    ]);

    $this->post(route('ltu.invitation.accept.store', ['token' => $invite->token]), [
        'name' => $translator->name,
        'email' => $invite->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('ltu.translation.index'));

    $user = Contributor::where('email', $invite->email)->first();

    $this->assertAuthenticatedAs($user, 'translations');
});

test('invitation accept store will redirect if contributor already exists', function () {
    $invite = Invite::factory()->create();

    $contributor = Contributor::factory()->create([
        'email' => $invite->email,
        'role' => RoleEnum::translator,
    ]);

    $this->post(route('ltu.invitation.accept.store', ['token' => $invite->token]), [
        'name' => $contributor->name,
        'email' => $invite->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('ltu.login'));

    $this->assertGuest('translations');
});
