<?php

namespace Outhebox\Translations\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Http\Requests\Auth\AcceptInviteRequest;
use Outhebox\Translations\Models\Contributor;

class InviteController extends Controller
{
    public function show(string $token): Response|RedirectResponse
    {
        $contributor = Contributor::query()
            ->where('invite_token', $token)
            ->first();

        if (! $contributor || $contributor->invite_expires_at->isPast()) {
            return redirect()->route('ltu.login')
                ->with('error', 'This invitation link is invalid or has expired.');
        }

        return Inertia::render('translations/auth/accept-invite', [
            'token' => $token,
            'contributor' => [
                'name' => $contributor->name,
                'email' => $contributor->email,
            ],
        ]);
    }

    public function accept(AcceptInviteRequest $request, string $token): RedirectResponse
    {
        $contributor = Contributor::query()
            ->where('invite_token', $token)
            ->first();

        if (! $contributor || $contributor->invite_expires_at->isPast()) {
            return redirect()->route('ltu.login')
                ->with('error', 'This invitation link is invalid or has expired.');
        }

        $contributor->update([
            'password' => $request->validated('password'),
            'invite_token' => null,
            'invite_expires_at' => null,
        ]);

        Auth::guard('translations')->login($contributor);

        $request->session()->regenerate();

        return redirect()->route('ltu.languages.index');
    }
}
