<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Outhebox\Translations\Http\Requests\UpdateAccountPasswordRequest;
use Outhebox\Translations\Http\Requests\UpdateAccountRequest;
use Outhebox\Translations\Services\TranslationAuth;

class AccountController extends Controller
{
    private function auth(): TranslationAuth
    {
        return app(TranslationAuth::class);
    }

    public function update(UpdateAccountRequest $request): RedirectResponse
    {
        $this->auth()->user()->update($request->validated());

        return redirect()->back()->with('success', 'Profile updated.');
    }

    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $this->auth()->user()->update([
            'password' => $request->validated('password'),
        ]);

        return redirect()->back()->with('success', 'Password updated.');
    }
}
