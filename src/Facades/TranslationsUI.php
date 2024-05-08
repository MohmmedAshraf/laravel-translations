<?php

namespace Outhebox\TranslationsUI\Facades;

use Illuminate\Support\Facades\Facade;
use Outhebox\TranslationsUI\TranslationsManager;

/**
 * @method static array getTranslations(string $locale)
 * @method static array getLocales()
 * @method static string|null download()
 * @method static void export(bool $download = false)
 * @method static string|null getDomain()
 * @method static void setDomain(string $domain)
 * @method static string getPath()
 * @method static void setPath(string $path)
 * @method static string getLocale()
 * @method static void setLocale(string $locale)
 * @method static string getFallback()
 * @method static array getMiddleware()
 * @method static void setMiddleware(array $middleware)
 * @method static string|null getConnection()
 * @method static void setConnection(string $connection)
 * @method static bool getIncludeFileInKey()
 * @method static void setIncludeFileInKey(bool $bool)
 * @method static string getSourceLanguage()
 * @method static void setSourceLanguage(string $locale)
 * @method static array getExcludeFiles()
 * @method static void setExcludeFiles(array $files)
 *
 * @see TranslationsManager
 */
class TranslationsUI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'translations';
    }
}
