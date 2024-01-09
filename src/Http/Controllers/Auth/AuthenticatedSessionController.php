<?php

namespace Outhebox\LaravelTranslations\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Outhebox\LaravelTranslations\Http\Requests\LoginRequest;

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

        return redirect()->route('canvas');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('canvas')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('canvas.login');
    }
}
