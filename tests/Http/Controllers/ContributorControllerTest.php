<?php

use Illuminate\Support\Facades\Mail;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Mail\InviteCreated;
use Outhebox\TranslationsUI\Models\Invite;

it('it can render the contributor page', function () {
    $this->actingAs($this->owner, 'translations')->get(route('ltu.contributors.index'))
        ->assertStatus(200);
});

test('owner can invite a contributor', function () {
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

    expect($invite)->not->toBeNull();

    Mail::assertSent(InviteCreated::class, function ($mail) use ($invite) {
        return $mail->hasTo($invite->email);
    });
});

test('owner cannot invite a contributor with invalid email', function () {
    $this->actingAs($this->owner, 'translations')->post(route('ltu.contributors.invite.store'), [
        'email' => 'invalid-email',
        'role' => RoleEnum::owner->value,
    ])->assertSessionHasErrors('email');
});
