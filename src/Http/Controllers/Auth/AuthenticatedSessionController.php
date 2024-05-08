<?php

namespace Outhebox\TranslationsUI\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Outhebox\TranslationsUI\Enums\LocaleEnum;
use Outhebox\TranslationsUI\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        if (Auth::guard('translations')->check()) {
            return redirect()->route('ltu.translation.index');
        }

        return Inertia::render('auth/login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $locale = collect(LocaleEnum::toSelectArray())->where('code', Auth::guard('translations')->user()->lang)->first();

        return redirect()->route('ltu.translation.index')->cookie(cookie('translations_locale', $locale['code'], 576000));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('translations')->logout();

        Cookie::expire('translations_locale');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('ltu.login');
    }
}
