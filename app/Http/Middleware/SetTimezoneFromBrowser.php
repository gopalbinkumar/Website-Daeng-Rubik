<?php

namespace App\Http\Middleware;

use Closure;

class SetTimezoneFromBrowser
{
    public function handle($request, Closure $next)
    {
        if (session('timezone')) {
            config(['app.timezone' => session('timezone')]);
            date_default_timezone_set(session('timezone'));
        }

        return $next($request);
    }
}
