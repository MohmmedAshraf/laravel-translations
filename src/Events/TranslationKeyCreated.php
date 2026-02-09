<?php

namespace Outhebox\Translations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\TranslationKey;

class TranslationKeyCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TranslationKey $translationKey,
    ) {}
}
