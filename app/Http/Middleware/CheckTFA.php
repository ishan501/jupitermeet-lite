<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;


class CheckTFA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->tfa == 'active' && !Session::has('user_tfa')) {
        // if (auth()->user() && auth()->user()->tfa == 'active') {

            //prevent code from being sent multiple times
            if (!Session::has('user_tfa_sent')) {
                auth()->user()->generateCode();
                Session::put('user_tfa_sent', auth()->user()->id);
            }

            return redirect()->route('tfa.index');
        }

        return $next($request);
    }
}
