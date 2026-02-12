<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSourcePhraseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageKeys();
    }

    public function rules(): array
    {
        return [
            'value' => ['nullable', 'string'],
            'key' => ['sometimes', 'string', 'max:1000'],
            'group_id' => ['sometimes', 'integer', 'exists:ltu_groups,id'],
            'context_note' => ['nullable', 'string'],
            'priority' => ['sometimes', 'string', 'in:low,medium,high,critical'],
        ];
    }
}
