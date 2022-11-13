<?php

namespace Outhebox\LaravelTranslations;

trait AuthorizesRequests
{
    public static $authUsing;

    public static function auth($callback): static
    {
        static::$authUsing = $callback;

        return new static;
    }

    public static function check($request): bool|string
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }
}
