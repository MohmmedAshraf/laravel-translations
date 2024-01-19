<?php

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

test('password can be reset', function () {
    $token = encrypt($this->owner->id.'|'.Str::random());

    cache(["password.reset.{$this->owner->id}" => $token],
        now()->addMinutes(60)
    );

    $this->post(route('ltu.password.update', [
        'token' => $token,
        'email' => $this->owner->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]))->assertRedirect(route('ltu.translation.index'));

    expect(cache()->get("password.reset.{$this->owner->id}"))->toBeEmpty();
});

test('new password request will validate email', function () {
    $token = encrypt($this->owner->id.'|'.Str::random());

    $response = $this->post(route('ltu.password.update'), [
        'token' => $token,
        'email' => 'not-an-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($response->exception)->toBeInstanceOf(ValidationException::class);
});

test('new password request will validate unconfirmed password', function () {
    $token = encrypt($this->owner->id.'|'.Str::random());

    $response = $this->post(route('ltu.password.update'), [
        'token' => $token,
        'email' => $this->owner->email,
        'password' => 'password',
        'password_confirmation' => 'secret',
    ]);

    expect($response->exception)->toBeInstanceOf(ValidationException::class);
});

test('password will validate bad token', function () {
    $this->post(route('ltu.password.update'), [
        'token' => Str::random(),
        'email' => $this->owner->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHas('invalidResetToken');
});
