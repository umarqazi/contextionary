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
    public function locale($locale){
      MultiLang::setLocale($locale);
      session(['locale' => $locale]);
      $url=lang_URL('home');
      return redirect::back();
    }
    public function switchLanguage($locale){
      MultiLang::setLocale($locale);
      session(['locale' => $locale]);
      $url=lang_URL('home');
      return redirect::to($url);
    }
    public function dashboard(){
      die();
      return view::make('index');
    }
}
