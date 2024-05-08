<?php

namespace Outhebox\TranslationsUI\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'email' => ltu_trans('validation.attributes.email'),
            'password' => ltu_trans('validation.attributes.password'),
        ];
    }

    public function rules(): array
    {
        $this->redirect = route('ltu.login');

        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ltu_trans('validation.required'),
            '*.string' => ltu_trans('validation.string'),
            '*.email' => ltu_trans('validation.email'),
        ];
    }

    public function authenticate(): void
    {
        if (! Auth::guard('translations')->attempt($this->only('email', 'password'), $this->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => ltu_trans('auth.failed'),
            ]);
        }
    }
}
