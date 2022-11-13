<?php

use Illuminate\Support\Facades\Route;
use Outhebox\LaravelTranslations\Http\Controllers\TranslationController;

Route::get('/', [TranslationController::class, 'index'])->name('translations_ui.index');

Route::prefix('phrases')->group(function () {
    Route::get('{translation}', [TranslationController::class, 'phrases'])->name('translations_ui.phrases.index');
    Route::get('{translation}/edit/{phrase:uuid}', [TranslationController::class, 'phrase'])->name('translations_ui.phrases.show');
});
