<?php

namespace Outhebox\TranslationsUI\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Outhebox\TranslationsUI\Http\Resources\ContributorResource;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Models\Translation;

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
        return [
            ...parent::share($request),
            'auth' => $this->auth(),
            'canPublish' => (bool) Translation::count() > 0,
            'isProductionEnvironment' => app()->isProduction(),
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ];
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
