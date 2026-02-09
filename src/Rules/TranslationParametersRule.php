<?php

namespace Outhebox\Translations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Outhebox\Translations\Models\TranslationKey;

class TranslationParametersRule implements ValidationRule
{
    public function __construct(private TranslationKey $translationKey) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || ! $this->translationKey->hasParameters()) {
            return;
        }

        $missing = static::findMissing($this->translationKey, $value);

        if ($missing) {
            $fail('Missing required parameters: '.implode(', ', $missing));
        }
    }

    public static function findMissing(TranslationKey $translationKey, string $value): array
    {
        return array_values(array_filter(
            $translationKey->parameterNames(),
            fn (string $param) => ! str_contains($value, $param),
        ));
    }
}
