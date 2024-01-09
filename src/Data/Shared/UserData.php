<?php

namespace Outhebox\LaravelTranslations\Data\Shared;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?Carbon $email_verified_at,
    ) {
    }
}
