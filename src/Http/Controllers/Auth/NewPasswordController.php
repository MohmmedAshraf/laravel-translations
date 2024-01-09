<?php

namespace Outhebox\LaravelTranslations\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\LaravelTranslations\Models\Contributor;
use Throwable;

class NewPasswordController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('auth/reset-password', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            [$id, $token] = explode('|', decrypt($request->token));

            $user = Contributor::findOrFail($id);

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $user->password = Hash::make($request->password);

            $user->setRememberToken(Str::random(60));

            $user->save();

            Auth::guard('translations')->login($user);
        } catch (Throwable $e) {
            return redirect()->route('ltu.password.request')->with('invalidResetToken', 'Invalid token');
        }

        cache()->forget("password.reset.$id");

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return redirect()->route('ltu.translation.index');
    }
}
