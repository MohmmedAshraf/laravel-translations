<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SourcePhraseDestroyMultipleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'selected_ids' => ['required', 'array'],
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
