<?php

namespace Outhebox\Translations\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class TranslationsInertia extends Middleware
{
    protected $rootView = 'translations::app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'appearance' => 'system',
        ];
    }
}
