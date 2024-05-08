<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileUpdatePasswordRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'current_password' => ltu_trans('validation.attributes.current_password'),
            'password' => ltu_trans('validation.attributes.password'),
        ];
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.current_password' => ltu_trans('validation.max.string'),
            '*.min' => ltu_trans('validation.min.string'),
            '*.confirmed' => ltu_trans('validation.confirmed'),
        ];
    }
}
