<?php

use Illuminate\Support\Facades\Route;
use Outhebox\LaravelTranslations\Http\Controllers\Auth\AuthenticatedSessionController;
use Outhebox\LaravelTranslations\Http\Controllers\Auth\NewPasswordController;
use Outhebox\LaravelTranslations\Http\Controllers\Auth\PasswordResetLinkController;
use Outhebox\LaravelTranslations\Http\Controllers\PhraseController;
use Outhebox\LaravelTranslations\Http\Controllers\SourceTranslationController;
use Outhebox\LaravelTranslations\Http\Controllers\TranslationController;
use Outhebox\LaravelTranslations\Http\Middleware\Authenticate;
use Outhebox\LaravelTranslations\Http\Middleware\HandleInertiaRequests;

Route::middleware([
    'web',
    HandleInertiaRequests::class,
])->prefix('translations')->name('ltu.')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.attempt');

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

        Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/', [TranslationController::class, 'index'])->name('translation.index');
        Route::get('add-translation', [TranslationController::class, 'create'])->name('translation.create');
        Route::post('add-translation', [TranslationController::class, 'store'])->name('translation.store');

        Route::prefix('source-language')->group(function () {
            Route::get('/', [SourceTranslationController::class, 'index'])->name('translation.source_language');
            Route::get('create', [SourceTranslationController::class, 'create'])->name('translation.source_language.add_source_key');
            Route::get('/{phrase:uuid}', [SourceTranslationController::class, 'edit'])->name('translation.source_language.edit');
            Route::post('/{phrase:uuid}', [SourceTranslationController::class, 'update'])->name('translation.source_language.update');
        });

        Route::prefix('phrases/{translation}')->group(function () {
            Route::get('/', [PhraseController::class, 'index'])->name('phrases.index');
            Route::get('/edit/{phrase:uuid}', [PhraseController::class, 'edit'])->name('phrases.edit');
            Route::post('/edit/{phrase:uuid}', [PhraseController::class, 'update'])->name('phrases.update');

            Route::delete('delete', [TranslationController::class, 'destroy'])->name('translation.destroy');
        });

        Route::get('confirmation', [TranslationController::class, 'confirmationModal'])->name('confirmation');
    });
});
