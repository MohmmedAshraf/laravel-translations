<?php

namespace Outhebox\Translations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\Contributor;

class ContributorInvited
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Contributor $contributor,
        public string $inviteUrl,
    ) {}
}
