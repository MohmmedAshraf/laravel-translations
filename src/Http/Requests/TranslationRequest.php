<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'languages' => ltu_trans('validation.attributes.languages'),
        ];
    }

    public function rules(): array
    {
        return [
            'languages' => ['required', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.array' => ltu_trans('validation.array'),
        ];
    }
}
