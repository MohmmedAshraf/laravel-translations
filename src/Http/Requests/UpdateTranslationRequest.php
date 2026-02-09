<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Outhebox\Translations\Enums\TranslationStatus;

class UpdateTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app('translations.auth')->check();
    }

    public function rules(): array
    {
        return [
            'value' => ['nullable', 'string'],
            'status' => ['sometimes', Rule::enum(TranslationStatus::class)],
        ];
    }
}
