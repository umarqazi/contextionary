<?php

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use App\User;
use App\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\vClasses\RegistersUsers;
use Spatie\Permission\Models\Role;
use Input;
use Image;
use Illuminate\Support\Facades\Storage;
use Session;
use Redirect;
use View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public $redirectTo = '/verificationEmail';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $userServices;
    public function __construct(UserService $user)
    {
        $this->middleware('guest');
        $this->userServices=$user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'required|mimes:jpg,png,jpeg',
            'pseudonyme'=>'required|string',
            'gender'=>'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        try{
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'token' => md5(microtime()),
            ]);
            if (Input::hasFile('profile_image')) {
                $image      = Input::file('profile_image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();

                $img = Image::make($image->getRealPath());
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream();
                $fileName='images/'.$user->id.'/profile_image/'.$fileName;
                Storage::disk('public')->put($fileName, $img);
            }
            $user->profile_image=$fileName;
            $user->email_token= base64_encode($data['email']);
            $user->save();
            /**
             * Update User Profile
             */
            $userProfile = new UserProfile;
            $userProfile->pseudonyme = $data['pseudonyme'];
            $userProfile->date_birth=strtotime($data['date_birth']);
            $userProfile->gender= $data['gender'];
            $userProfile->phone_number=$data['phone_number'];
            $userProfile->country=$data['country'];
            $userProfile->native_language=$data['native_language'];
            $userProfile->user_id =$user->id;
            $userProfile->save();
            Session::put('user', $user);
            $this->redirectTo=$this->redirectTo.'/'.$user->id;

        }catch (\Exception $e){
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'danger'
            );
            return Redirect::back()->with($notification);
        }
    }
  
    public function sendVerificationEmail($id){
        $this->userServices->verificationEmail($id);
        $notification = array(
            'message' => t('You have successfully registered. An email is sent to you for verification.'),
            'alert_type' => 'success'
        );
        return Redirect::to('/login')->with($notification);
    }
  
    public function verifyEmail($token){
        $getUser=User::where('email_token', $token)->first();
        if($getUser){
            $getUser->status=1;
            $getUser->save();
            $notification = array(
                'message' => t('Your account has been activated. Please Login'),
                'alert_type' => 'success'
            );
            return Redirect::to('/login')->with($notification);
        }else{
            $notification = array(
                'message' => t('Token Mismatch error'),
                'alert_type' => 'danger'
            );
            return Redirect::to('/login')->with($notification);
        }
    }
}
