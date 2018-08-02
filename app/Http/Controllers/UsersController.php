<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Services\UserServices;

class UsersController extends Controller
{

    use ModelForm;
    protected  $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
        $this->middleware('auth');
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $user->delete();
    }
}
