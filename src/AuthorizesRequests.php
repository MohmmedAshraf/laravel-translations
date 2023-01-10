<?php

namespace Outhebox\LaravelTranslations;

trait AuthorizesRequests
{
    public static $authUsing;

    public static function auth($callback): self
    {
        static::$authUsing = $callback;

        return new static;
    }

    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }
}
