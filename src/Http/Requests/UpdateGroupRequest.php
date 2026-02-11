<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = app('translations.auth')->role();

        return $role && $role->canManageLanguages();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('ltu_groups')->where('namespace', $this->namespace)->ignore($this->route('group')),
            ],
            'namespace' => ['nullable', 'string', 'max:255'],
        ];
    }
}
