<?php

namespace Outhebox\Translations\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Services\TranslationAuth as TranslationAuthService;
use Symfony\Component\HttpFoundation\Response;

class TranslationsAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = app('translations.auth');

        if (! $auth->check()) {
            return $this->redirectToLogin($auth);
        }

        $user = $auth->user();

        if ($user instanceof Contributor && ! $user->is_active) {
            auth()->guard($auth->guardName())->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Your account has been deactivated.');
        }

        return $next($request);
    }

    private function redirectToLogin(TranslationAuthService $auth): RedirectResponse
    {
        if ($auth->isContributorMode()) {
            return redirect()->route('ltu.login');
        }

        return redirect()->to(config('translations.auth.login_url', '/login'));
    }
}
