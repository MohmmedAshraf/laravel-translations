<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Mail\InviteCreated;
use Outhebox\TranslationsUI\Models\Invite;
use Outhebox\TranslationsUI\Tests\TestCase;

class ContributorControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_render_the_contributor_page()
    {
        $this->actingAs($this->owner, 'translations')->get(route('ltu.contributors.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_owner_can_invite_a_contributor()
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        $response = $this->actingAs($this->owner, 'translations')->post(route('ltu.contributors.invite.store'), [
            'email' => 'email@example.com',
            'role' => RoleEnum::owner->value,
        ]);

        $response->assertRedirect(route('ltu.contributors.index').'#invited')
            ->assertSessionHas('notification', [
                'type' => 'success',
                'body' => 'Invite sent successfully',
            ]);

        $invite = Invite::where('email', 'email@example.com')->first();

        $this->assertNotNull($invite);

        Mail::assertSent(InviteCreated::class, function ($mail) use ($invite) {
            return $mail->hasTo($invite->email);
        });
    }

    /** @test */
    public function test_owner_cannot_invite_a_contributor_with_invalid_email()
    {
        $this->actingAs($this->owner, 'translations')->post(route('ltu.contributors.invite.store'), [
            'email' => 'invalid-email',
            'role' => RoleEnum::owner->value,
        ])->assertSessionHasErrors('email');
    }
}
