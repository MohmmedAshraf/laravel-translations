<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageLanguages();
    }

    public function rules(): array
    {
        return [
            'language_ids' => ['required', 'array'],
            'language_ids.*' => ['integer', 'exists:ltu_languages,id'],
        ];
    }
}
