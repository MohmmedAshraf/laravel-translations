<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    /** @test */
    public function login_page_can_be_rendered()
    {
        $this->get(route('ltu.login'))
            ->assertStatus(200);

    }

    public function test_login_request_will_validate_email(): void
    {
        $response = $this->post(route('ltu.login.attempt'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ])->assertRedirect(route('ltu.login'));

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function test_login_request_will_validate_password(): void
    {
        $response = $this->post(route('ltu.login.attempt'), [
            'email' => $this->owner->email,
            'password' => 'what-is-my-password',
        ])->assertSessionHasErrors();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function test_login_request_will_authenticate_user(): void
    {
        $this->withoutExceptionHandling();

        $user = Contributor::factory([
            'role' => RoleEnum::owner,
            'password' => Hash::make('password'),
        ])->create();

        $this->post(route('ltu.login.attempt'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('ltu.translation.index'));
    }

    public function test_authenticated_users_can_access_dashboard(): void
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.login'))
            ->assertRedirect(route('ltu.translation.index'));
    }

    public function test_authenticated_users_can_logout(): void
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.logout'))
            ->assertRedirect(route('ltu.login'));
    }
}
