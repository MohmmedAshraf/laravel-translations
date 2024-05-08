<?php

namespace Outhebox\TranslationsUI\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'token' => ltu_trans('validation.attributes.token'),
            'email' => ltu_trans('validation.attributes.email'),
            'password' => ltu_trans('validation.attributes.password'),
        ];
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.string' => ltu_trans('validation.string'),
            '*.email' => ltu_trans('validation.email'),
            '*.confirmed' => ltu_trans('validation.confirmed'),
            '*.min' => ltu_trans('validation.min.string'),
        ];
    }
}
