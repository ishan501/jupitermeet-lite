<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (getSetting('VERIFY_USERS') == 'enabled' && $request->user() && !$request->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }

        return $next($request);
    }
}
