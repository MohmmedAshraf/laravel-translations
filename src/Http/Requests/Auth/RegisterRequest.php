<?php

namespace Outhebox\Translations\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:ltu_contributors,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
