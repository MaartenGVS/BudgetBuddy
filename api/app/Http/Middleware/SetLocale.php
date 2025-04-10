<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{

    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = 'en';
        $availableLanguages = config('language.available_languages');
        $locale = $request->header('Accept-Language', $defaultLocale);

        if (!in_array($locale, $availableLanguages)) {
            $locale = $defaultLocale;
        }

        App::setLocale($locale);
        return $next($request);
    }
}
