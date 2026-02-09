<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canTranslate();
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:ltu_groups,id'],
            'key' => ['required', 'string', 'max:1000'],
            'value' => ['nullable', 'string'],
        ];
    }
}
