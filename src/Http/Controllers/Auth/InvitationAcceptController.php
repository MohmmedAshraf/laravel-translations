<?php

namespace Outhebox\TranslationsUI\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Outhebox\TranslationsUI\Http\Requests\Auth\InvitationAcceptRequest;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Models\Invite;

class InvitationAcceptController extends BaseController
{
    public function create(string $token)
    {
        if (! $invite = Invite::where('token', $token)->first()) {
            abort(404);
        }

        return Inertia::render('auth/invite/accept', [
            'email' => $invite->email,
            'token' => $invite->token,
        ]);
    }

    public function store(InvitationAcceptRequest $request): RedirectResponse
    {
        $request->validated();

        if (! $invite = Invite::where('token', $request->input('token'))->first()) {
            abort(404);
        }

        if (Contributor::where('email', $invite->email)->first()) {
            return redirect()->route('ltu.login')
                ->with('notification', [
                    'type' => 'error',
                    'body' => ltu_trans('You already have an account, please login'),
                ]);
        }

        $user = Contributor::create([
            'role' => $invite->role,
            'email' => $invite->email,
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);

        $invite->delete();

        auth('translations')->login($user);

        return redirect()->route('ltu.translation.index');
    }

    public function destroy(Invite $invite)
    {
        $invite->delete();

        return redirect()->route('ltu.contributors.index')->withFragment('#invited')->with('notification', [
            'type' => 'success',
            'body' => ltu_trans('Invitation deleted successfully'),
        ]);
    }
}
