<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Outhebox\TranslationsUI\Enums\LocaleEnum;

class ProfileUpdateLanguageRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'language' => ltu_trans('validation.attributes.language'),
        ];
    }

    public function rules(): array
    {
        $locales = [];
        foreach (LocaleEnum::toSelectArray() as $value) {
            $locales[] = $value['code'];
        }

        return [
            'language' => ['required', Rule::in($locales)],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.in' => ltu_trans('validation.in'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'language' => Arr::get($this->language, 'code'),
        ]);
    }
}
