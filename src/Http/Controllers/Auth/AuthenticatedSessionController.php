<?php

namespace Outhebox\TranslationsUI\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Outhebox\TranslationsUI\Http\Requests\LoginRequest;

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

        return redirect()->route('ltu.translation.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('translations')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('ltu.login');
    }
}
