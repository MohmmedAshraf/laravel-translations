<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\TranslationsUI\Enums\LocaleEnum;
use Outhebox\TranslationsUI\Facades\TranslationsUI;
use Outhebox\TranslationsUI\Http\Requests\ProfileUpdateLanguageRequest;
use Outhebox\TranslationsUI\Http\Requests\ProfileUpdatePasswordRequest;
use Outhebox\TranslationsUI\Http\Requests\ProfileUpdateRequest;

class ProfileController extends BaseController
{
    public function edit(Request $request): Response
    {
        return Inertia::render('profile/edit', [
            'status' => session('status'),
            'locales' => collect(LocaleEnum::toSelectArray())->toArray(),
            'localeSelected' => collect(LocaleEnum::toSelectArray())->where('code', Auth::guard('translations')->user()->lang)->first(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->validated();

        $request->user()->update($request->only('name', 'email'));

        return redirect()->route('ltu.profile.edit');
    }

    public function updatePassword(ProfileUpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('ltu.profile.edit')
            ->with('status', 'password-updated');
    }

    public function updateLanguage(ProfileUpdateLanguageRequest $request): RedirectResponse
    {
        $request->validated();

        $request->user()->update([
            'lang' => $request->input('language'),
        ]);

        TranslationsUI::setLocale($request->input('language'));

        return redirect()->route('ltu.profile.edit')
            ->with('status', 'language-updated')->cookie(cookie('translations_locale', $request->input('language'), 576000));
    }
}
