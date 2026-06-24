<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPaymentMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (getSetting('AUTH_MODE') == 'enabled' && getSetting('PAYMENT_MODE') == 'enabled' && count(paymentGateways()) != 0) {
            return $next($request);
        }

        return redirect('/');
    }
}
