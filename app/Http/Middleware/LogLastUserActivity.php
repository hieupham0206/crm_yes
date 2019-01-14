<?php

namespace App\Http\Middleware;

use Closure;

class LogLastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user     = \Auth::user();
        if ($user) {
            $user->setCache(5);
        }

        return $response;
    }
}
