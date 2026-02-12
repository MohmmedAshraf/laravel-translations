<?php

namespace Outhebox\Translations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\Language;

class LanguageAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Language $language,
    ) {}
}
