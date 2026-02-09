<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageLanguages();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'namespace' => ['nullable', 'string', 'max:255'],
        ];
    }
}
