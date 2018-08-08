<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Redirect;
use MultiLang;
use Route;

class LocaleController extends Controller
{
    public function locale($locale){
      MultiLang::setLocale($locale);
      session(['locale' => $locale]);
      $url=lang_URL('home');
      return redirect::back();
    }
}