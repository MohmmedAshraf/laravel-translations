<?php

use Illuminate\Support\Facades\Route;
use Outhebox\LaravelTranslations\Http\Controllers\TranslationController;

Route::controller(TranslationController::class)
    ->as('translations_ui.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::prefix('phrases')
            ->as('phrases.')
            ->group(function () {
                Route::get('{translation}', 'phrases')->name('index');
                Route::get('{translation}/edit/{phrase:uuid}', 'phrase')->name('show');
            });
    });
