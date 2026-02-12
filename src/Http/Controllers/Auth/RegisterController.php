<?php

namespace Outhebox\Translations\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Http\Requests\Auth\RegisterRequest;
use Outhebox\Translations\Models\Contributor;

class RegisterController extends Controller
{
    public function show(): Response
    {
        $this->ensureRegistrationEnabled();

        return Inertia::render('translations/auth/register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $this->ensureRegistrationEnabled();

        $contributor = Contributor::query()->create([
            ...$request->validated(),
            'role' => ContributorRole::from(config('translations.default_role', 'translator')),
        ]);

        Auth::guard('translations')->login($contributor);

        $request->session()->regenerate();

        return redirect()->route('ltu.languages.index');
    }

    private function ensureRegistrationEnabled(): void
    {
        abort_unless(config('translations.registration', true), 404);
    }
}
