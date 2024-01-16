<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Outhebox\TranslationsUI\Tests\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NewPasswordControllerTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function test_password_can_be_reset(): void
    {
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

        $this->assertEmpty(cache()->get("password.reset.{$this->owner->id}"));
    }

    public function test_new_password_request_will_validate_email(): void
    {
        $token = encrypt($this->owner->id.'|'.Str::random());

        $response = $this->post(route('ltu.password.update'), [
            'token' => $token,
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function test_new_password_request_will_validate_unconfirmed_password(): void
    {
        $token = encrypt($this->owner->id.'|'.Str::random());

        $response = $this->post(route('ltu.password.update'), [
            'token' => $token,
            'email' => $this->owner->email,
            'password' => 'password',
            'password_confirmation' => 'secret',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function test_password_will_validate_bad_token(): void
    {
        $this->post(route('ltu.password.update'), [
            'token' => Str::random(),
            'email' => $this->owner->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHas('invalidResetToken');
    }
}
