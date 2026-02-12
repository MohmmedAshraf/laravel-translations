<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Outhebox\Translations\Enums\ContributorRole;

class UpdateContributorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageContributors();
    }

    public function rules(): array
    {
        return [
            'role' => ['required', Rule::enum(ContributorRole::class)],
            'language_ids' => ['nullable', 'array'],
            'language_ids.*' => ['integer', 'exists:ltu_languages,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
