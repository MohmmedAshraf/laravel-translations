<?php

namespace Outhebox\Translations\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\Contributor;

class ContributorInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contributor $contributor,
        public string $inviteUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to collaborate on translations",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'translations::mail.contributor-invite',
            with: [
                'contributor' => $this->contributor,
                'inviteUrl' => $this->inviteUrl,
                'expiresDays' => config('translations.invite.expires_days', 7),
            ],
        );
    }
}
