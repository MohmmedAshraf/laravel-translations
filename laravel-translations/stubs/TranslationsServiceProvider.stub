<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Outhebox\LaravelTranslations\LaravelTranslationsApplicationServiceProvider;

class TranslationsServiceProvider extends LaravelTranslationsApplicationServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    protected function gate()
    {
        Gate::define('viewLaravelTranslationsUI', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
