<?php

namespace Outhebox\LaravelTranslations;

use Illuminate\Support\Facades\File;
use RuntimeException;

class LaravelTranslations
{
    use AuthorizesRequests;

    public static function assetsAreCurrent(): bool
    {
        $publishedPath = public_path('vendor/translations/mix-manifest.json');

        if (! File::exists($publishedPath)) {
            throw new RuntimeException('Laravel Translations UI assets are not published. Please run: php artisan translations:publish');
        }

        return File::get($publishedPath) === File::get(__DIR__.'/../public/mix-manifest.json');
    }
}
