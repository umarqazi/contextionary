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

        if ($request->user()->user_roles == 7) {
            return redirect('/en/active-plan');
        }else{
            return $next($request);
        }
    }
}
