<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

class ProfileUpdateRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'name' => ltu_trans('validation.attributes.name'),
            'email' => ltu_trans('validation.attributes.email'),
        ];
    }

    public function rules(): array
    {
        $connection = TranslationsUI::getConnection();

        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'string', 'lowercase', 'unique:'.($connection ? $connection.'.' : '').'ltu_contributors,email,'.$this->user()->id],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.max:255' => ltu_trans('validation.max.string'),
            '*.email' => ltu_trans('validation.email'),
            '*.string' => ltu_trans('validation.string'),
            '*.lowercase' => ltu_trans('validation.lowercase'),
            '*.unique' => ltu_trans('validation.unique'),
        ];
    }
}
