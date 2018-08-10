<?php

namespace App\Http\Controllers;

use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use App\User;
use App\TransactionHistory;
use App\Services\UserServices;
use App\Services\RoleServices;
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

    public function __construct(UserServices $userServices, RoleServices $role)
    {
        $this->userServices = $userServices;
        $this->userRoles = $role;
    }
    public function home(){
        return view::make('index');
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

    public function profileEdit()
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

    public function profileUpdate()
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
        }
        else {
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

    public function destroy($id)
    {
        // delete
        $user = User::find($id);
        $user->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the user!');
        return back();
    }

    public function destroyAJAX($id)
    {
        // delete
        $user = User::find($id);
        $this->userServices->delete($user);
    }

    public function selectPlan($token){
        $user=Session::get('user');
        $authenticateToken= $this->userServices->authenticateToken($user->id, $token);
        if($authenticateToken==false):
            return Redirect::to('/register');
        endif;

        $updateToken=$this->userServices->updateToken($user->id);
        return view::make('user.user_plan.select_plan')->with(['user'=>$user, 'token'=>$updateToken]);
    }

    public function userPlan($id, $token){
        $authenticateToken=$this->userServices->authenticateToken($id, $token);
        if($authenticateToken==false):
            return Redirect::to('/register');
        endif;
        $updateToken=$this->userServices->updateToken($id);
        return view::make('user.user_plan.user_plan')->with(['id'=> $id, 'token'=>$updateToken]);
    }

    public function showPaymentInfo($id, $plan, $token){
        $authenticateToken=$this->userServices->authenticateToken($id, $token);
        if($authenticateToken==false):
            return Redirect::to('/register');
        endif;
        return view::make('user.user_plan.pay_with_stripe')->with(['id'=>$id, 'plan'=>$plan, 'token'=>$token]);
    }
}
