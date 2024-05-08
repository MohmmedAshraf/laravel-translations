<?php

namespace Outhebox\TranslationsUI\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class InvitationAcceptRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'name' => ltu_trans('validation.attributes.name'),
            'token' => ltu_trans('validation.attributes.token'),
            'password' => ltu_trans('validation.attributes.password'),
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.string' => ltu_trans('validation.string'),
            '*.confirmed' => ltu_trans('validation.confirmed'),
            '*.min' => ltu_trans('validation.min.string'),
        ];
    }
}
