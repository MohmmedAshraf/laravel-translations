<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyBulkSourcePhraseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageKeys();
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:ltu_translation_keys,id'],
        ];
    }
}
