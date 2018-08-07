<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\TransactionHistory;
use App\Http\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use View;
use Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    protected  $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
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

    public function edit()
    {
        $user = Auth::user();
        return view('user.edit', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        $user = User::with('userProfile')->find($user->id);
        return view('user.profile', compact('user'));
    }

    public function update()
    {
        // validate
        $rules = array(
            'name'       => 'required',
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
            $user->name       = Input::get('name');
            $user->email      = Input::get('email');
            if(Input::get('password') != ''){
                $user->password = bcrypt(Input::get('password'));
            }
            $user->save();

            // redirect
            Session::flash('message', 'Successfully updated your Profile!');
            return Redirect::to('profile');
        }
    }
    public function selectPlan($token){
      $user=Session::get('user');
      $authenticateToken=UserServices::authenticateToken($user->id, $token);
      if($authenticateToken==false):
        return Redirect::to('/register');
      endif;

      $updateToken=UserServices::updateToken($user->id);
      return view::make('user.user_plan.select_plan')->with(['user'=>$user, 'token'=>$updateToken]);
    }
    public function userPlan($id, $token){
      $authenticateToken=UserServices::authenticateToken($id, $token);
      if($authenticateToken==false):
        return Redirect::to('/register');
      endif;
      $updateToken=UserServices::updateToken($id);
      return view::make('user.user_plan.user_plan')->with(['id'=> $id, 'token'=>$updateToken]);
    }
    public function showPaymentInfo($id, $plan, $token){
      $authenticateToken=UserServices::authenticateToken($id, $token);
      if($authenticateToken==false):
        return Redirect::to('/register');
      endif;
      return view::make('user.user_plan.pay_with_stripe')->with(['id'=>$id, 'plan'=>$plan, 'token'=>$token]);
    }
    public static function updateTransaction($transaction, $user_id, $package_id){
      $pTransaction=UserServices::pTransaction($transaction, $user_id, $package_id);
      $assignRole=UserServices::assignRole($user_id, $package_id);
      return $pTransaction;
    }
}
