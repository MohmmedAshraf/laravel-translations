<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateAccountPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $auth = app('translations.auth');

        return $auth->isContributorMode() && $auth->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:translations'],
            'password' => ['required', 'confirmed', Password::default()],
        ];
    }
}
