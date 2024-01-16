<?php

namespace Outhebox\TranslationsUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $this->redirect = route('ltu.login');

        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    public function authenticate(): void
    {
        if (! Auth::guard('translations')->attempt($this->only('email', 'password'), $this->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }
}
