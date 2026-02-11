<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Rules\TranslationParametersRule;
use Outhebox\Translations\Rules\TranslationPluralRule;

class UpdatePhraseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canEditTranslations();
    }

    public function rules(): array
    {
        $translationKey = $this->route('translationKey');

        return [
            'value' => ['nullable', 'string', new TranslationParametersRule($translationKey), new TranslationPluralRule($translationKey)],
            'status' => ['sometimes', Rule::enum(TranslationStatus::class)],
            'reviewer_feedback' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
