<?php

namespace Outhebox\Translations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Outhebox\Translations\Services\TranslationAuth;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        $auth = app(TranslationAuth::class);

        return $auth->isContributorMode() && $auth->user() !== null;
    }

    public function rules(): array
    {
        $user = app(TranslationAuth::class)->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('ltu_contributors', 'email')->ignore($user->getTranslationId())],
        ];
    }
}
