<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

class SourcePhraseUpdateRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'note' => ltu_trans('validation.attributes.note'),
            'phrase' => ltu_trans('validation.attributes.phrase'),
            'file' => ltu_trans('validation.attributes.file'),
        ];
    }

    public function rules(): array
    {
        $connection = TranslationsUI::getConnection();

        return [
            'note' => ['nullable', 'string'],
            'phrase' => ['required', 'string'],
            'file' => [
                'required',
                'integer',
                'exists:'.($connection ? $connection.'.' : '').'ltu_translation_files,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            '*.string' => ltu_trans('validation.string'),
            '*.required' => ltu_trans('validation.required'),
            '*.integer' => ltu_trans('validation.integer'),
            '*.exists' => ltu_trans('validation.exists'),
        ];
    }
}
