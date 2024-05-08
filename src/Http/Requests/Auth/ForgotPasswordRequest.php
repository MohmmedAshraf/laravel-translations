<?php

namespace Outhebox\TranslationsUI\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

class ForgotPasswordRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'email' => ltu_trans('validation.attributes.email'),
        ];
    }

    public function rules(): array
    {
        $connection = TranslationsUI::getConnection();

        return [
            'email' => [
                'required',
                'email',
                'exists:'.($connection ? $connection.'.' : '').'ltu_contributors,email',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.email' => ltu_trans('validation.email'),
            '*.exists' => ltu_trans('validation.exists'),
        ];
    }
}
