<?php

use Illuminate\Support\Facades\Route;
use Outhebox\Translations\Http\Controllers\Auth\InviteController;
use Outhebox\Translations\Http\Controllers\Auth\LoginController;
use Outhebox\Translations\Http\Controllers\Auth\RegisterController;

Route::middleware('guest:translations')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('ltu.login');
    Route::post('/login', [LoginController::class, 'store'])->name('ltu.login.store');

    if (config('translations.registration', true)) {
        Route::get('/register', [RegisterController::class, 'show'])->name('ltu.register');
        Route::post('/register', [RegisterController::class, 'store'])->name('ltu.register.store');
    }

    Route::get('/invite/{token}', [InviteController::class, 'show'])->name('ltu.invite.show');
    Route::post('/invite/{token}', [InviteController::class, 'accept'])->name('ltu.invite.accept');
});

Route::middleware('translations.auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('ltu.logout');
});
