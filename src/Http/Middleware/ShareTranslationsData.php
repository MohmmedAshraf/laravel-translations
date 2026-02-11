<?php

namespace Outhebox\Translations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareTranslationsData
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = app('translations.auth');

        Inertia::share([
            'auth' => [
                'user' => fn () => $auth->check() ? $auth->user() : null,
                'role' => fn () => $auth->role()?->value,
            ],
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'),
                'info' => $request->session()->get('info'),
            ],
            'environment' => fn () => app()->environment(),
            'translationsNav' => fn () => $this->navigationItems(),
            'isContributorMode' => fn () => $auth->isContributorMode(),
        ]);

        return $next($request);
    }

    protected function navigationItems(): array
    {
        return [
            [
                'label' => 'Translations',
                'route' => 'ltu.languages.index',
                'icon' => 'languages',
            ],
            [
                'label' => 'Groups',
                'route' => 'ltu.groups.index',
                'icon' => 'library',
            ],
        ];
    }
}
