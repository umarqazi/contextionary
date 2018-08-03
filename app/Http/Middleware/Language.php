<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use MultiLang;

class Language
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
         if (Session::has('locale')) {
             MultiLang::setLocale(Session::get('locale'));
         }
         else { // This is optional as Laravel will automatically set the fallback language if there is none specified
             MultiLang::setLocale(Config::get('app.fallback_locale'));
         }
         return $next($request);
     }
}
