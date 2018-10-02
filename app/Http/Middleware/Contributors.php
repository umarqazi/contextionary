<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Config;

class Contributors
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
            if(Auth::user()->hasRole(Config::get('constant.contributorRole'))){
                return $next($request);
            }
        }
        $route=lang_route('dashboard');
        return redirect($route);
    }
}
