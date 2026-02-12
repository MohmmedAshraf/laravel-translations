<?php

namespace Outhebox\Translations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Outhebox\Translations\Enums\ContributorRole;
use Symfony\Component\HttpFoundation\Response;

class TranslationsRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $auth = app('translations.auth');

        if (! $auth->check()) {
            abort(403, 'Unauthorized.');
        }

        $requiredRole = ContributorRole::from($role);
        $userRole = $auth->role();

        if (! $userRole || ! $userRole->isAtLeast($requiredRole)) {
            abort(403, 'Insufficient permissions.');
        }

        return $next($request);
    }
}
