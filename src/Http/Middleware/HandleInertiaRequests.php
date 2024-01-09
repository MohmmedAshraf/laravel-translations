<?php

namespace Outhebox\LaravelTranslations\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Outhebox\LaravelTranslations\Concerns\UsesAuth;
use Outhebox\LaravelTranslations\Data\Shared\SharedData;
use Outhebox\LaravelTranslations\Data\Shared\UserData;
use Outhebox\LaravelTranslations\Http\Resources\ContributorResource;
use Outhebox\LaravelTranslations\Models\Contributor;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    use UsesAuth;

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
        $state = new SharedData(
            user: fn () => Auth::check() ? UserData::from(Auth::user()) : null,
        );

        return array_merge(parent::share($request), $state->toArray(), [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }

    protected function auth(): array
    {
        if (! self::getAuthGuard()->check()) {
            return [
                'user' => null,
            ];
        }

        $user = self::getAuthGuard()->user();

        // If `Auth Middleware` was not resolved first
        // return empty auth
        if (! $user instanceof Contributor) {
            return [];
        }

        return [
            'user' => new ContributorResource($user),
        ];
    }
}
