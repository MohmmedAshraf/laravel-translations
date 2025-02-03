<?php

use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\TranslationsUI\Http\Resources\PhraseResource;

it('withoutWrapping does not affect JsonResource superclass', function () {

    $translationsManager = new \Outhebox\TranslationsUI\TranslationsUIServiceProvider($this->app);
    $translationsManager->packageBooted();

    expect(JsonResource::$wrap)->toBe('data')
        ->and(PhraseResource::$wrap)->toBe(null);
});
