<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Redirect;
use MultiLang;
use Route;
use View;

class LocaleController extends Controller
{
    /**
     * @param $locale
     * @return mixed
     */
    public function locale($locale){
      MultiLang::setLocale($locale);
      session(['locale' => $locale]);
      $url=lang_URL('dashboard');
      return redirect::to($url);
    }

    /**
     * @param $locale
     * @return mixed
     */
    public function switchLanguage($locale){
      MultiLang::setLocale($locale);
      session(['locale' => $locale]);
      $url=lang_URL('home');
      return redirect::to($url);
    }
}
