<?php

use Illuminate\Support\Facades\Route;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

beforeEach(function () {
    Translation::resetStaticState();
});

function useContributorAuth(): void
{
    config(['translations.auth.driver' => 'contributors']);
    config(['auth.guards.translations' => [
        'driver' => 'session',
        'provider' => 'translations_contributors',
    ]]);
    config(['auth.providers.translations_contributors' => [
        'driver' => 'eloquent',
        'model' => Contributor::class,
    ]]);

    $routeFile = realpath(__DIR__.'/../src/routes/auth.php');

    if ($routeFile && ! Route::has('ltu.login')) {
        Route::middleware(config('translations.middleware', ['web']))
            ->prefix(config('translations.path', 'translations'))
            ->group($routeFile);
    }
}
