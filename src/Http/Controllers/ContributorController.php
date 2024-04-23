<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Http\Resources\ContributorResource;
use Outhebox\TranslationsUI\Http\Resources\InviteResource;
use Outhebox\TranslationsUI\Mail\InviteCreated;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Models\Invite;

class ContributorController extends BaseController
{
    public function index(): Response
    {
        return Inertia::render('contributor/index', [
            'invited' => InviteResource::collection(Invite::paginate(10)),
            'contributors' => ContributorResource::collection(Contributor::paginate(10)),
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('contributor/modals/invite', [
            'roles' => RoleEnum::toSelectArray(),
        ])->baseRoute('ltu.contributors.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $connection = config('translations.database_connection');
        $request->validate([
            'role' => 'required|integer',
            'email' => 'required|email|unique:'.($connection ? $connection.'.' : '').'ltu_contributors,email',
        ]);

        do {
            $token = Str::random(32);
        } while (Invite::where('token', $token)->first());

        $invite = Invite::create([
            'token' => $token,
            'role' => $request->get('role'),
            'email' => $request->get('email'),
        ]);

        Mail::to($request->get('email'))->send(new InviteCreated($invite));

        return redirect()->route('ltu.contributors.index')->withFragment('#invited')->with('notification', [
            'type' => 'success',
            'body' => 'Invite sent successfully',
        ]);
    }

    public function destroy(Contributor $contributor): RedirectResponse
    {
        $contributor->delete();

        return redirect()->route('ltu.contributors.index')->with('notification', [
            'type' => 'success',
            'body' => 'Contributor deleted successfully',
        ]);
    }
}
