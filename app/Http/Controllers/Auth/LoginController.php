<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepo;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App;
use Illuminate\Support\Facades\Auth;
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
        $this->roleRepo         =   new RoleRepo();
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
        try{
            $user = Socialite::driver($provider)->user();

            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);
            return redirect($this->redirectTo);
        }catch (\Exception $e){
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            $url=lang_url('login');
            return Redirect::to($url)->with($notification);
        }

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
                'first_name'    => $user->name,
                'last_name'     => NULL,
                'password'      => NULL,
                'timezone'      => NULL,
                'coins'         => 100,
                'email'         => $user->email,
                'provider'      => $provider,
                'status'        => 1,
                'provider_id'   => $user->id
            ]);
            $profile    =   App\Profile::create(['user_id'=>$getUser->id]);
        }else{
            $this->userService->updateRecord(['id'=>$getUser->id], ['provider'=>$provider, 'provider_id'=>$user->id]);
        }

        return $getUser;
    }

    /**
     * @param $email
     * @param $social
     * @param $password
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticate($email, $social, $password)
    {
        $credentials = [
            'email' => $email,
        ];
        if ($social == true) {
            $authUser = User::where('provider_id', $password)->first();
            if ($authUser) {

                Auth::login($authUser, true);
            } else {

                return redirect(lang_url('login'));
            }
        } else {
            $credentials['password'] = $password;
            Auth::attempt($credentials);
        }

        if (Auth::check()) {

            $this->roleRepo->assignMultiRole(\auth()->id(), 'premium plan');
            return redirect('learning-center');
        } else {

            return redirect(lang_url('login'));
        }
    }
}
