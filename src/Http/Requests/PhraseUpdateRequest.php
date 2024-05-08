<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhraseUpdateRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'phrase' => ltu_trans('validation.attributes.phrase'),
        ];
    }

    public function rules(): array
    {
        return [
            'phrase' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.string' => ltu_trans('validation.string'),
        ];
    }
}
