<?php

namespace Outhebox\TranslationsUI\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Outhebox\TranslationsUI\Http\Resources\ContributorResource;
use Outhebox\TranslationsUI\Models\Contributor;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'translations::app';

    public function version(Request $request): bool|string|null
    {
        if (file_exists($manifest = public_path('vendor/translations-ui/manifest.json'))) {
            return md5_file($manifest);
        }

        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => $this->auth(),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'notification' => fn () => $request->session()->get('notification'),
            'status' => fn () => $request->session()->get('status'),
        ]);
    }

    protected function auth(): array
    {
        if (! Auth::guard('translations')->check()) {
            return [
                'user' => null,
            ];
        }

        $user = Auth::guard('translations')->user();

        if (! $user instanceof Contributor) {
            return [];
        }

        return [
            'user' => new ContributorResource($user),
        ];
    }
}
