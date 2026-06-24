<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed')) && !Str::contains($request->path(), ['install'])) {
            $locale = session('locale') ?? getDefaultLanguage()->code;
            App::setLocale($locale);
            
            if(Auth::check()) {
                $user = Auth::user();
                if ($user->status == 'inactive') {
                    Auth::logout();
                    return redirect()->route('login');
                }
            }   
        }
            
        return $next($request);
    }
}