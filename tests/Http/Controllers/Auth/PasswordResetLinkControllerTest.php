<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Outhebox\TranslationsUI\Mail\ResetPassword;

test('forgot password link screen can be rendered', function () {
    $this->get(route('ltu.password.request'))
        ->assertOk();
});

test('forgot password link request will validate email', function () {
    $response = $this->post(route('ltu.password.email'), [
        'email' => 'not-an-email',
    ]);

    $this->assertInstanceOf(ValidationException::class, $response->exception);
});

test('the password reset link can sent', function () {
    Mail::fake();

    $this->post(route('ltu.password.email'), [
        'email' => $this->owner->email,
    ])
        ->assertRedirect(route('ltu.password.request'));

    Mail::assertSent(ResetPassword::class, function ($mail) {
        $this->assertIsString($mail->token);

        return $mail->hasTo($this->owner->email);
    });
});
