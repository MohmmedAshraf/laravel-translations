<?php

namespace Outhebox\LaravelTranslations;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class LaravelTranslationsApplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->authorization();
    }

    protected function authorization()
    {
        $this->gate();

        LaravelTranslations::auth(function ($request) {
            return app()->environment('local') ||
                Gate::check('viewLaravelTranslationsUI', [$request->user()]);
        });
    }

    protected function gate()
    {
        Gate::define('viewLaravelTranslationsUI', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    public function register()
    {
        //
    }
}
