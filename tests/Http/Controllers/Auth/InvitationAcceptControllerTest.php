<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers\Auth;

use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Models\Invite;
use Outhebox\TranslationsUI\Tests\TestCase;

class InvitationAcceptControllerTest extends TestCase
{
    /** @test */
    public function invitation_accept_page_can_be_rendered()
    {
        $invite = Invite::factory()->create();

        $this->get(route('ltu.invitation.accept', ['token' => $invite->token]))
            ->assertStatus(200);
    }

    /** @test */
    public function invitation_accept_page_returns_404_if_token_is_invalid()
    {
        $this->get(route('ltu.invitation.accept', ['token' => 'invalid-token']))
            ->assertStatus(404);
    }

    /** @test */
    public function invitation_accept_store_will_create_contributor_and_login()
    {
        $this->withoutExceptionHandling();

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
    }

    /** @test */
    public function invitation_accept_store_will_redirect_if_contributor_already_exists()
    {
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
    }
}
