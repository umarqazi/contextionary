<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App;
use Auth;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/validateRole';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     protected function credentials(\Illuminate\Http\Request $request)
     {
         return ['email' => $request->email, 'password' => $request->password, 'status' => 1];
     }
    public function __construct()
    {
        $this->redirectTo = lang_url('validateRole');
        $this->middleware('guest')->except('logout');
    }
    public function logout(){
         Auth::logout();
         $url=lang_route('home');
         return Redirect::to($url);
    }
}
