<?php

namespace Outhebox\LaravelTranslations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Outhebox\LaravelTranslations\LaravelTranslations
 */
class LaravelTranslations extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Outhebox\LaravelTranslations\LaravelTranslations::class;
    }
}
