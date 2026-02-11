<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Outhebox\Translations\Http\Requests\UpdateAccountPasswordRequest;
use Outhebox\Translations\Http\Requests\UpdateAccountRequest;

class AccountController extends Controller
{
    public function update(UpdateAccountRequest $request): RedirectResponse
    {
        app('translations.auth')->user()->update($request->validated());

        return redirect()->back()->with('success', 'Profile updated.');
    }

    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        app('translations.auth')->user()->update([
            'password' => $request->validated('password'),
        ]);

        return redirect()->back()->with('success', 'Password updated.');
    }
}
