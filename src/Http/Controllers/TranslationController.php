<?php

namespace Outhebox\LaravelTranslations\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller as BaseController;
use Outhebox\LaravelTranslations\Http\Middleware\Authorize;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;

class TranslationController extends BaseController
{
    public function __construct()
    {
        $this->middleware(Authorize::class);
    }

    public function index(): View
    {
        return view('translations::index', [
            'installed' => Language::count() > 0 && Translation::count() > 0,
        ]);
    }

    public function phrases(Translation $translation): View
    {
        return view('translations::phrases', [
            'translation' => $translation,
        ]);
    }

    public function phrase(Translation $translation, Phrase $phrase): View
    {
        return view('translations::phrase', [
            'phrase' => $phrase,
            'translation' => $translation,
        ]);
    }
}
