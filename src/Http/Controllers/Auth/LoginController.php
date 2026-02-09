<?php

namespace Outhebox\Translations\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('translations/auth/login', [
            'canRegister' => config('translations.registration', true),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->validated();

        if (! Auth::guard('translations')->attempt($credentials, $request->boolean('remember'))) {
            $request->hitRateLimiter();

            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $request->clearRateLimiter();
        $request->session()->regenerate();

        return redirect()->intended(route('ltu.languages.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('translations')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ltu.login');
    }
}
