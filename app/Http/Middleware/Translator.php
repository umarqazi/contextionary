<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Auth;

class Translator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate'))){
                return $next($request);
            }
        }
        $route=lang_route('dashboard');
        return redirect($route);
    }
}