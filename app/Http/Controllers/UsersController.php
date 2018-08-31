<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use App\User;
use App\Transaction;
use App\Profile;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\RoleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use View;
use Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\UserRepo;

class UsersController extends Controller
{

    use ModelForm;
    protected  $userServices;
    protected  $userRoles;
    protected  $authService;
    protected  $user;
    protected $register;

    public function __construct(RegisterController $registerController, UserService $userServices, RoleService $role, AuthService $authService)
    {
        $this->userServices = $userServices;
        $this->userRoles = $role;
        $this->authService = $authService;
        $this->register=$registerController;
    }
    public function validateRole(){
        if(Auth::check()){
            $userRole=$this->userRoles->checkRole(Auth::user()->id);
            if(!$userRole->isEmpty()){
                return Redirect::to(lang_route('dashboard'));
            }else{
                return Redirect::to(lang_route('selectPlan'));
            }
        }

    }
    public function index(){
        return view::make('home');
    }
    public function userCount(){
        $countUsers = $this->userServices->countUsers();
        return view('admin::dashboard.blocks',
            [
                'value'      => $countUsers,
                'label'      => 'Users',
                'url'        => '#url',
                'urlLabel'   => 'All Users'
            ]
        );
    }
    public function edit(){
        $user = Auth::user();
        return view::make('user.edit')->with('user', $user);
    }
    public function profileUpdate(Request $request)
    {
        // validate
        $rules = array(
            'first_name'       => 'required',
            'last_name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'nullable|min:6|confirmed'
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return Redirect::to('/edit-profile')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // update
            $user = Auth::user();
            $user->first_name       = Input::get('first_name');
            $user->last_name       = Input::get('last_name');
            $user->email      = Input::get('email');
            $fileName=$user->profile_image;
            if(Input::get('password') != ''){
                $user->password = bcrypt(Input::get('password'));
            }

            if (Input::hasFile('profile_image')) {
                $image      = Input::file('profile_image');
                $fileName=$this->register->uploadImage($image, $user->id);
            }
            $user->profile_image=$fileName;
            $user->save();
            $updateProfile=Profile::find($user->id);
            $updateProfile->phone_number=Input::get('phone_number');
            $updateProfile->gender=Input::get('gender');
            $updateProfile->country=Input::get('country');
            $updateProfile->native_language=Input::get('native_language');
            $updateProfile->pseudonyme=Input::get('pseudonyme');
            $updateProfile->bio=Input::get('bio');
            $updateProfile->save();
            // redirect
            $notification = array(
                'message' => 'Successfully updated your Profile!',
                'alert_type' => 'success'
            );
            return Redirect::to('profile')->with($notification);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        $user = User::with('profile')->find($user->id);
        return view('user.profile', compact('user'));
    }

    public function destroy($id)
    {
        // delete
        $user = User::find($id);
        $user->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the user!');
        return back();
    }

    public function selectPlan(){
        $this->user = Auth::user();
        return view::make('user.user_plan.select_plan')->with(['user'=>$this->user]);
    }

    public function userPlan(){
        $this->user = Auth::user();
        return view::make('user.user_plan.user_plan')->with(['id'=> $this->user->id]);
    }

    public function showPaymentInfo($plan){
        $this->user = Auth::user();
        return view::make('user.user_plan.pay_with_stripe')->with(['id'=>$this->user->id, 'plan'=>$plan]);
    }
}
