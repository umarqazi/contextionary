<?php

namespace App\Http\Controllers;

use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use App\User;
use App\Transaction;
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

    public function __construct(UserService $userServices, RoleService $role, AuthService $authService)
    {
        $this->userServices = $userServices;
        $this->userRoles = $role;
        $this->authService = $authService;
        $this->user=Auth::user();
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
    public function profileEdit()
    {
        $user = $this->user;
        return view('user.edit', compact('user'));
    }

    public function profile()
    {
        $user = $this->user;
        $user = User::with('userProfile')->find($user->id);
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
        return view::make('user.user_plan.select_plan')->with(['user'=>$this->user]);
    }

    public function userPlan(){
        return view::make('user.user_plan.user_plan')->with(['id'=> $this->user->id]);
    }

    public function showPaymentInfo($plan){
        return view::make('user.user_plan.pay_with_stripe')->with(['id'=>$this->user->id, 'plan'=>$plan]);
    }
}
