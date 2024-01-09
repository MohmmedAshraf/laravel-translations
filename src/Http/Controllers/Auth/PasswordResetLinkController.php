<?php

namespace Outhebox\LaravelTranslations\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\LaravelTranslations\Mail\ResetPassword;
use Outhebox\LaravelTranslations\Models\Contributor;

class PasswordResetLinkController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('auth/forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:lts_contributors,email',
        ]);

        $token = Str::random();

        $user = Contributor::firstWhere('email', $request->email);

        if ($user) {
            cache(["password.reset.$user->id" => $token],
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