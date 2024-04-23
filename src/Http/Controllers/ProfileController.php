<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends BaseController
{
    public function edit(Request $request): Response
    {
        return Inertia::render('profile/edit', [
            'status' => session('status'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $connection = config('translations.database_connection');
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.($connection ? $connection.'.' : '').'ltu_contributors,email,'.$request->user()->id],
        ]);

        $request->user()->update($request->only('name', 'email'));

        return redirect()->route('ltu.profile.edit');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('ltu.profile.edit')
            ->with('status', 'password-updated');
    }
}
