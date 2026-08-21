<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! in_array($locale, ['es', 'en'])) {
            $locale = $request->getPreferredLanguage(['es', 'en']) ?? config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
