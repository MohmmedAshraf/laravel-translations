<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageLanguages();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'regex:/^[a-z]{2,3}(-[A-Za-z]{2,4})?$/', 'unique:ltu_languages,code'],
            'name' => ['required', 'string', 'max:255'],
            'native_name' => ['nullable', 'string', 'max:255'],
            'rtl' => ['boolean'],
        ];
    }
}
