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
    public function __construct()
    {
        $this->middleware('guest');
        $this->userServices=new UserService();
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
            'profile_image' => 'mimes:jpg,png,jpeg',
            'pseudonyme'=>'string|nullable',
            'gender'=>'required',
            'phone_number'=>'required',
            'native_language'=>'required',
            'timezone'=>'required'
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
        $fileName='';
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'status' => 0,
            'password' => bcrypt($data['password']),
            'token' => md5(microtime()),
            'email_token' => base64_encode($data['email']),
            'timezone' => $data['timezone'],
        ]);
        /**
         * Update User Profile
         */
        $id=$user->id;
        $userProfile = new Profile;
        $userProfile->pseudonyme = $data['pseudonyme'];
        $userProfile->date_birth=$data['date_birth'];
        $userProfile->gender= $data['gender'];
        $userProfile->phone_number=$data['phone_number'];
        $userProfile->country=$data['country'];
        $userProfile->native_language=$data['native_language'];
        $userProfile->user_id =$id;
        $userProfile->save();
        if (Input::hasFile('profile_image')) {
            $image      = Input::file('profile_image');
            $fileName=$this->uploadImage($image, $user->id);
        }

        /**
         * update profile image
         */
        if($fileName){
            $user->profile_image=$fileName;
            $user->save();
        }
        Session::put('user', $user);
    }

    public function sendVerificationEmail($id=NULL){
        if($id==NULL){
            $user=User::where('status',0)->orderBy('id', 'desc')->select('id')->first();
            $id=$user->id;
        }
        if($id){
            $this->userServices->verificationEmail($id);
        }
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
    public function uploadImage($data, $user_id){
        $fileName   = time() . '.' . $data->getClientOriginalExtension();

        $img = Image::make($data->getRealPath());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        $fileName='images/'.$user_id.'/profile_image/'.$fileName;
        Storage::disk('public')->put($fileName, $img);
        return $fileName;
    }
}
