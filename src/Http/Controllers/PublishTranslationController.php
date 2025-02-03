<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Outhebox\TranslationsUI\TranslationsManager;

class PublishTranslationController
{
    public function publish(): RedirectResponse
    {
        app(TranslationsManager::class)->export();

        return redirect()->route('ltu.translation.index');
    }

    public function download()
    {
        $downloadPath = app(TranslationsManager::class)->download();

        if (! $downloadPath) {
            return redirect()->route('ltu.translation.index');
        }

        return response()->download($downloadPath, 'lang.zip');
    }
}
