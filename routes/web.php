<?php

use Illuminate\Support\Facades\Route;
use Outhebox\TranslationsUI\Http\Controllers\Auth\AuthenticatedSessionController;
use Outhebox\TranslationsUI\Http\Controllers\Auth\InvitationAcceptController;
use Outhebox\TranslationsUI\Http\Controllers\Auth\NewPasswordController;
use Outhebox\TranslationsUI\Http\Controllers\Auth\PasswordResetLinkController;
use Outhebox\TranslationsUI\Http\Controllers\ContributorController;
use Outhebox\TranslationsUI\Http\Controllers\PhraseController;
use Outhebox\TranslationsUI\Http\Controllers\ProfileController;
use Outhebox\TranslationsUI\Http\Controllers\SourcePhraseController;
use Outhebox\TranslationsUI\Http\Controllers\TranslationController;
use Outhebox\TranslationsUI\Http\Middleware\Authenticate;
use Outhebox\TranslationsUI\Http\Middleware\HandleInertiaRequests;
use Outhebox\TranslationsUI\Http\Middleware\RedirectIfNotOwner;

Route::domain(config('translations.domain'))->group(function () {
    Route::middleware(array_merge(config('translations.middleware'), [HandleInertiaRequests::class]))->prefix(config('translations.path'))->name('ltu.')->group(function () {
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

        // Authenticated Routes
        Route::middleware(Authenticate::class)->group(function () {
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

            // Contributors Routes
            Route::prefix('contributors')->group(function () {
                Route::prefix('invite')->middleware(RedirectIfNotOwner::class)->group(function () {
                    Route::get('/', [ContributorController::class, 'create'])->name('contributors.invite');
                    Route::post('/', [ContributorController::class, 'store'])->name('contributors.invite.store');

                    Route::delete('{invite}/delete', [InvitationAcceptController::class, 'destroy'])->name('contributors.invite.delete');
                });

                Route::get('/', [ContributorController::class, 'index'])->name('contributors.index');

                Route::delete('{contributor}/delete', [ContributorController::class, 'destroy'])
                    ->middleware(RedirectIfNotOwner::class)
                    ->name('contributors.delete');
            });

            // Profile Routes
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        });
    });
});
