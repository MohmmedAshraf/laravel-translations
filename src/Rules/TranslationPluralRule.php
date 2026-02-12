<?php

namespace Outhebox\Translations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

class TranslationPluralRule implements ValidationRule
{
    public function __construct(private TranslationKey $translationKey) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->translationKey->is_plural || empty($value)) {
            return;
        }

        $sourceTranslation = Translation::query()
            ->where('translation_key_id', $this->translationKey->id)
            ->whereHas('language', fn ($q) => $q->where('is_source', true))
            ->value('value');

        if (! $sourceTranslation) {
            return;
        }

        $sourceSegments = count(explode('|', $sourceTranslation));
        $translationSegments = count(explode('|', $value));

        if ($translationSegments !== $sourceSegments) {
            $fail("Plural translation must have {$sourceSegments} variants separated by pipes (|), got {$translationSegments}.");
        }
    }
}
