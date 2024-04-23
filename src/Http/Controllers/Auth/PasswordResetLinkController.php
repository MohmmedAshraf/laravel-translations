<?php

namespace Outhebox\TranslationsUI\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\TranslationsUI\Mail\ResetPassword;
use Outhebox\TranslationsUI\Models\Contributor;

class PasswordResetLinkController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('auth/forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $connection = config('translations.database_connection');
        $request->validate([
            'email' => 'required|email|exists:'.($connection ? $connection.'.' : '').'ltu_contributors,email',
        ]);

        $token = Str::random();

        $user = Contributor::firstWhere('email', $request->email);

        if ($user) {
            cache(
                ["password.reset.$user->id" => $token],
                now()->addMinutes(60)
            );

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            Mail::to($user->email)->send(new ResetPassword(encrypt("{$user->id}|{$token}")));
        }

        return redirect()
            ->route('ltu.password.request')
            ->with('status', 'We have emailed your password reset link!');
    }
}
