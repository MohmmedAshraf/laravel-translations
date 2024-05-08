<?php

namespace Outhebox\TranslationsUI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Outhebox\TranslationsUI\Enums\LocaleEnum;
use Outhebox\TranslationsUI\Facades\TranslationsUI;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Locale is enabled and allowed to be change
        $locales = [];
        foreach (LocaleEnum::toSelectArray() as $value) {
            $locales[] = $value['code'];
        }

        TranslationsUI::setLocale(TranslationsUI::getFallback());
        if (Cookie::has('translations_locale') && in_array(Cookie::get('translations_locale'), $locales)) {
            TranslationsUI::setLocale(Cookie::get('translations_locale'));
        }

        return $next($request);
    }
}
