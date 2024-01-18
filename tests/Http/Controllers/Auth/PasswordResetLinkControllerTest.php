<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Outhebox\TranslationsUI\Mail\ResetPassword;
use Outhebox\TranslationsUI\Tests\TestCase;

class PasswordResetLinkControllerTest extends TestCase
{
    /** @test */
    public function forgot_password_link_screen_can_be_rendered(): void
    {
        $this->get(route('ltu.password.request'))
            ->assertOk();
    }

    /** @test */
    public function forgot_password_link_request_will_validate_email(): void
    {
        $response = $this->post(route('ltu.password.email'), [
            'email' => 'not-an-email',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    /** @test */
    public function the_password_reset_link_can_sent(): void
    {
        Mail::fake();

        $this->post(route('ltu.password.email'), [
            'email' => $this->owner->email,
        ])
            ->assertRedirect(route('ltu.password.request'));

        Mail::assertSent(ResetPassword::class, function ($mail) {
            $this->assertIsString($mail->token);

            return $mail->hasTo($this->owner->email);
        });
    }
}
