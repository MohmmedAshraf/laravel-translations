<?php

use Illuminate\Support\Facades\Route;
use Outhebox\Translations\Http\Controllers\ContributorController;
use Outhebox\Translations\Http\Controllers\GroupController;
use Outhebox\Translations\Http\Controllers\ImportExportController;
use Outhebox\Translations\Http\Controllers\LanguageController;
use Outhebox\Translations\Http\Controllers\PhraseController;
use Outhebox\Translations\Http\Controllers\SourcePhraseController;

Route::middleware(['translations.auth'])->group(function () {
    Route::get('/', [LanguageController::class, 'index'])->name('ltu.languages.index');
    Route::get('/phrases/{language}', [PhraseController::class, 'index'])->name('ltu.phrases.index');
    Route::get('/phrases/{language}/edit/{translationKey}', [PhraseController::class, 'edit'])->name('ltu.phrases.edit');
    Route::get('/source-language', [SourcePhraseController::class, 'index'])->name('ltu.source.index');
    Route::get('/source-language/{translationKey}', [SourcePhraseController::class, 'show'])->name('ltu.source.show');
    Route::get('/groups', [GroupController::class, 'index'])->name('ltu.groups.index');

    Route::middleware(['translations.role:translator'])->group(function () {
        Route::put('/phrases/{language}/{translationKey}', [PhraseController::class, 'update'])->name('ltu.phrases.update');
    });

    Route::middleware(['translations.role:admin'])->group(function () {
        Route::post('/languages', [LanguageController::class, 'store'])->name('ltu.languages.store');
        Route::post('/languages/custom', [LanguageController::class, 'storeCustom'])->name('ltu.languages.store-custom');
        Route::delete('/languages/{language}', [LanguageController::class, 'destroy'])->name('ltu.languages.destroy');
        Route::post('/languages/bulk/{action}', [LanguageController::class, 'bulkAction'])->name('ltu.languages.bulk-action');

        Route::post('/source-language/bulk/{action}', [SourcePhraseController::class, 'bulkAction'])->name('ltu.source.bulk-action');
        Route::post('/source-language', [SourcePhraseController::class, 'store'])->name('ltu.source.store');
        Route::put('/source-language/{translationKey}', [SourcePhraseController::class, 'update'])->name('ltu.source.update');
        Route::delete('/source-language/{translationKey}', [SourcePhraseController::class, 'destroy'])->name('ltu.source.destroy');
        Route::delete('/source-language', [SourcePhraseController::class, 'destroyBulk'])->name('ltu.source.destroy-bulk');

        Route::post('/groups', [GroupController::class, 'store'])->name('ltu.groups.store');
        Route::put('/groups/{group}', [GroupController::class, 'update'])->name('ltu.groups.update');
        Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('ltu.groups.destroy');

        Route::post('/import', [ImportExportController::class, 'import'])->name('ltu.import');
        Route::post('/export', [ImportExportController::class, 'export'])->name('ltu.export');
        Route::get('/import/status', [ImportExportController::class, 'importStatus'])->name('ltu.import.status');
        Route::get('/export/status', [ImportExportController::class, 'exportStatus'])->name('ltu.export.status');

        Route::get('/contributors', [ContributorController::class, 'index'])->name('ltu.contributors.index');
        Route::post('/contributors', [ContributorController::class, 'store'])->name('ltu.contributors.store');
        Route::put('/contributors/{contributor}', [ContributorController::class, 'update'])->name('ltu.contributors.update');
        Route::post('/contributors/{contributor}/toggle-active', [ContributorController::class, 'toggleActive'])->name('ltu.contributors.toggle-active');
        Route::delete('/contributors/{contributor}', [ContributorController::class, 'destroy'])->name('ltu.contributors.destroy');
    });
});
