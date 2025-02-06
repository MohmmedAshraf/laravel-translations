<?php

namespace Outhebox\TranslationsUI\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Outhebox\TranslationsUI\Models\Invite;

class InviteCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invite $invite) {}

    public function build(): static
    {
        return $this->subject('You have been invited to join the translation team')
            ->markdown('translations::mail.invite', [
                'link' => route('ltu.invitation.accept', $this->invite->token),
            ]);
    }
}
