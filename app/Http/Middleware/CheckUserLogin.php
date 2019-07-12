<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;

class CheckUserLogin extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next, ...$guards)
     {
        foreach ($guards as $guard) {
            if ($guard != 'api') {
                $route = lang_route('login');
                return redirect($route);
            }
        }

         $this->authenticate($guards);
         return $next($request);
     }
}
