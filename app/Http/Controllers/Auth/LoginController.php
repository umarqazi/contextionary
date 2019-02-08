<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App;
use Auth;
use Redirect;
use App\User;
use Socialite;

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

    protected $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected function credentials(\Illuminate\Http\Request $request)
    {
        return ['email' => $request->email, 'password' => $request->password, 'status' => 1];
    }

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->redirectTo = lang_url('validateRole');
        $this->userService     =   new App\Services\UserService();
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return mixed
     */
    public function logout(){
        Auth::logout();
        $url=lang_route('home');
        return Redirect::to($url);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        $user = User::where($this->username(), $request->{$this->username()})->first();

        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->status != 1) {
            $errors = [$this->username() => trans('auth.notactivated'), 'resend'=>$user->id];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
    /**social Login***/

    /**
     * @param $provider
     * @return mixed
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /***
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /**
     * @param $user
     * @param $provider
     * @return mixed
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        $getUser    =   $this->userService->getUser(['email'=>$user->email]);
        if(!$getUser){
            $getUser   =   User::create([
                'first_name'     => $user->name,
                'last_name'     => NULL,
                'password'     => NULL,
                'timezone'     => NULL,
                'coins'     => 100,
                'email'    => $user->email,
                'provider' => $provider,
                'provider_id' => $user->id
            ]);
            $profile    =   App\Profile::create(['user_id'=>$getUser->id]);
        }else{
            $this->userService->updateRecord(['id'=>$getUser->id], ['provider'=>$provider, 'provider_id'=>$user->id]);
        }

        return $getUser;
    }
}
