<?php

use Illuminate\Support\Facades\Route;
use Outhebox\TranslationsUI\Http\Controllers\Auth\AuthenticatedSessionController;
use Outhebox\TranslationsUI\Http\Controllers\Auth\InvitationAcceptController;
use Outhebox\TranslationsUI\Http\Controllers\Auth\NewPasswordController;
use Outhebox\TranslationsUI\Http\Controllers\Auth\PasswordResetLinkController;
use Outhebox\TranslationsUI\Http\Controllers\ContributorController;
use Outhebox\TranslationsUI\Http\Controllers\PhraseController;
use Outhebox\TranslationsUI\Http\Controllers\ProfileController;
use Outhebox\TranslationsUI\Http\Controllers\SourceTranslationController;
use Outhebox\TranslationsUI\Http\Controllers\TranslationController;
use Outhebox\TranslationsUI\Http\Middleware\Authenticate;
use Outhebox\TranslationsUI\Http\Middleware\HandleInertiaRequests;

Route::middleware([
    'web',
    HandleInertiaRequests::class,
])->prefix('translations')->name('ltu.')->group(function () {
    Route::prefix('auth')->group(function () {

        Route::prefix('invite')->group(function () {
            Route::get('accept/{token}', [InvitationAcceptController::class, 'create'])->name('invitation.accept');
            Route::post('accept', [InvitationAcceptController::class, 'store'])->name('invitation.accept.store');
        });

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

        Route::prefix('source-translation')->group(function () {
            Route::get('/', [SourceTranslationController::class, 'index'])->name('source_translation');
            Route::post('import', [SourceTranslationController::class, 'import'])->name('source_translation.import');
            Route::get('create', [SourceTranslationController::class, 'create'])->name('source_translation.add_source_key');
            Route::post('create', [SourceTranslationController::class, 'store'])->name('source_translation.store');
            Route::post('delete-phrases', [SourceTranslationController::class, 'destroy_multiple'])->name('source_translation.delete_phrases');

            Route::prefix('{phrase:uuid}')->group(function () {
                Route::get('/', [SourceTranslationController::class, 'edit'])->name('source_translation.edit');
                Route::post('/', [SourceTranslationController::class, 'update'])->name('source_translation.update');
                Route::delete('delete', [SourceTranslationController::class, 'destroy'])->name('source_translation.delete_phrase');
            });
        });

        Route::prefix('phrases')->group(function () {
            Route::prefix('{translation}')->group(function () {
                Route::get('/', [PhraseController::class, 'index'])->name('phrases.index');
                Route::get('/edit/{phrase:uuid}', [PhraseController::class, 'edit'])->name('phrases.edit');
                Route::post('/edit/{phrase:uuid}', [PhraseController::class, 'update'])->name('phrases.update');
                Route::delete('delete', [TranslationController::class, 'destroy'])->name('translation.destroy');
            });

            Route::post('delete-multiple', [TranslationController::class, 'destroy_multiple'])->name('translation.destroy.multiple');
        });

        Route::prefix('contributors')->group(function () {
            Route::prefix('invite')->group(function () {
                Route::get('/', [ContributorController::class, 'create'])->name('contributors.invite');
                Route::post('/', [ContributorController::class, 'store'])->name('contributors.invite.store');

                Route::delete('{invite}/delete', [InvitationAcceptController::class, 'destroy'])->name('contributors.invite.delete');
            });

            Route::get('/', [ContributorController::class, 'index'])->name('contributors.index');
            Route::delete('{contributor}/delete', [ContributorController::class, 'destroy'])->name('contributors.delete');
        });

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});
