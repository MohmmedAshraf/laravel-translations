<?php

namespace Outhebox\TranslationsUI\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

if (class_exists('App\Exceptions\Handler')) {
    class ExtendedHandler extends Handler
    {
        // Extend the custom handler
    }
} else {
    class ExtendedHandler extends BaseHandler
    {
        // Extend the default handler
    }
}

class TranslationsUIExceptionHandler extends ExtendedHandler
{
    public static function isTranslationsUIRequest(Request $request): bool
    {
        $path = 'translations';

        return $request->is($path) || $request->is("$path/*");
    }

    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if (self::isTranslationsUIRequest($request)) {
            return $this->renderInertiaException($request, $this->prepareException($e));
        }

        return parent::render($request, $e);
    }

    /**
     * @throws Throwable
     */
    protected function renderInertiaException($request, $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : ($e->status ?? 500);

        Inertia::setRootView('translations::app');

        if ($statusCode === 403) {
            return Inertia::render('error', [
                'code' => '403',
                'title' => 'Access forbidden!',
                'text' => 'Sorry, you are forbidden from accessing this page.',
            ])->toResponse($request)->setStatusCode($statusCode);
        }

        if ($statusCode === 404) {
            return Inertia::render('error', [
                'code' => '404',
                'title' => 'Page not found',
                'text' => 'Sorry, the page you are looking for could not be found.',
            ])->toResponse($request)->setStatusCode($statusCode);
        }

        if ($statusCode === 500 && ! $e instanceof AuthenticationException && ! App::hasDebugModeEnabled()) {
            return Inertia::render('error', [
                'code' => '500',
                'title' => 'Internal server error',
                'text' => 'Sorry, something went wrong.',
            ])->toResponse($request)->setStatusCode(500);
        }

        return parent::render($request, $e);
    }
}
