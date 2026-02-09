<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Outhebox\Translations\Enums\ContributorRole;

class StoreContributorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:ltu_contributors,email'],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::enum(ContributorRole::class)],
            'language_ids' => ['nullable', 'array'],
            'language_ids.*' => ['integer', 'exists:ltu_languages,id'],
        ];
    }
}
