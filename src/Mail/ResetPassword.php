<?php

namespace Outhebox\TranslationsUI\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function build(): static
    {
        return $this->subject('Reset your password')
            ->markdown('translations::mail.password', [
                'link' => route('ltu.password.reset', ['token' => $this->token]),
            ]);
    }
}
