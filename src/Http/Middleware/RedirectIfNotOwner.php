<?php

namespace Outhebox\TranslationsUI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotOwner
{
    public function handle(Request $request, Closure $next)
    {
        if (! currentUser()->isOwner()) {
            return redirect()->back()->with('notification', [
                'type' => 'error',
                'body' => 'You are not allowed to perform this action',
            ]);
        }

        return $next($request);
    }
}
