<?php

namespace Outhebox\Translations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\Translation;

class TranslationSaved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Translation $translation,
        public ?string $oldValue = null,
        public ?string $changeType = null,
        public ?string $changedBy = null,
        public ?array $metadata = null,
    ) {}
}
