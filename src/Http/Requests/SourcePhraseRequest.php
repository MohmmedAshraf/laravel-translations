<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

class SourcePhraseRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'key' => ltu_trans('validation.attributes.key'),
            'file' => ltu_trans('validation.attributes.file'),
            'content' => ltu_trans('validation.attributes.content'),
        ];
    }

    public function rules(): array
    {
        $connection = TranslationsUI::getConnection();

        return [
            'key' => ['required', 'regex:/^[\w. ]+$/u'],
            'file' => [
                'required',
                'integer',
                'exists:'.($connection ? $connection.'.' : '').'ltu_translation_files,id',
            ],
            'content' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.regex' => ltu_trans('validation.regex'),
            '*.integer' => ltu_trans('validation.integer'),
            '*.exists' => ltu_trans('validation.exists'),
            '*.string' => ltu_trans('validation.string'),
        ];
    }
}
