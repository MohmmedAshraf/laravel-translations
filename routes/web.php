<?php

use Illuminate\Support\Facades\Route;
use Outhebox\TranslationsUI\Http\Controllers\PhraseController;
use Outhebox\TranslationsUI\Http\Controllers\SourcePhraseController;
use Outhebox\TranslationsUI\Http\Controllers\TranslationController;
use Outhebox\TranslationsUI\Http\Middleware\HandleInertiaRequests;
use Outhebox\TranslationsUI\Http\Middleware\RedirectIfNotOwner;

Route::domain(config('translations.domain'))->group(function () {
    Route::middleware(array_merge(config('translations.middleware'), [HandleInertiaRequests::class]))->prefix(config('translations.path'))->name('ltu.')->group(function () {
        // Translation Routes
        Route::get('/', [TranslationController::class, 'index'])->name('translation.index');

        Route::middleware(RedirectIfNotOwner::class)->group(function () {
            Route::get('publish', [TranslationController::class, 'publish'])->name('translation.publish');
            Route::post('publish', [TranslationController::class, 'export'])->name('translation.export');
            Route::get('download', [TranslationController::class, 'download'])->name('translation.download');
        });

        Route::get('add-translation', [TranslationController::class, 'create'])->name('translation.create');
        Route::post('add-translation', [TranslationController::class, 'store'])->name('translation.store');

        // Source Phrase Routes
        Route::prefix('source-translation')->group(function () {
            Route::get('/', [SourcePhraseController::class, 'index'])->name('source_translation');
            Route::get('create', [SourcePhraseController::class, 'create'])->name('source_translation.add_source_key');
            Route::post('create', [SourcePhraseController::class, 'store'])->name('source_translation.store_source_key');

            Route::post('delete-phrases', [SourcePhraseController::class, 'destroy_multiple'])
                ->middleware(RedirectIfNotOwner::class)
                ->name('source_translation.delete_phrases');

            Route::prefix('{phrase:uuid}')->group(function () {
                Route::get('/', [SourcePhraseController::class, 'edit'])->name('source_translation.edit');
                Route::post('/', [SourcePhraseController::class, 'update'])->name('source_translation.update');

                Route::delete('delete', [SourcePhraseController::class, 'destroy'])
                    ->middleware(RedirectIfNotOwner::class)
                    ->name('source_translation.delete_phrase');
            });
        });

        // Phrase Routes
        Route::prefix('phrases')->group(function () {
            Route::prefix('{translation}')->group(function () {
                Route::get('/', [PhraseController::class, 'index'])->name('phrases.index');
                Route::get('/edit/{phrase:uuid}', [PhraseController::class, 'edit'])->name('phrases.edit');
                Route::post('/edit/{phrase:uuid}', [PhraseController::class, 'update'])->name('phrases.update');

                Route::delete('delete', [TranslationController::class, 'destroy'])
                    ->middleware(RedirectIfNotOwner::class)
                    ->name('translation.delete');
            });

            Route::post('delete-multiple', [TranslationController::class, 'destroy_multiple'])
                ->middleware(RedirectIfNotOwner::class)
                ->name('translation.delete_multiple');
        });
    });
});
