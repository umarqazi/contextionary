<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;
class CheckGuestUser
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
        if ($request->user()->hasRole('guest')) {
            return redirect(lang_route('activeUserPlan'));
        }else{
            return $next($request);
        }
    }
}