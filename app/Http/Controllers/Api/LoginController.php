<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\Services\UserService;
use App\User;
use App\UserGamePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->userService     =   new UserService();
    }

    /**
     *
    @SWG\Post(
     *     path="/login",
     *     description="User Login here",
     *
    @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="Enter your email",
     *         required=true,
     *     ),
     *
    @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="Enter your password",
     *         required=true,
     *     ),
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    function login(Request $request){

        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()){

            return json($validate->errors(), 400);
        }

        $user_credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if ($request->social == true) {

            $authUser = User::where('provider_id', $request->password)->first();
            if (!empty($authUser)) {

                $data = [
                    'user_id' => $authUser->id,
                    'api_token' => $authUser->api_token,
                    'game_coins' => $authUser->game_coins,
                    'game_points' => UserGamePoint::select('game_id', 'points')->where(['user_id' => $authUser->id])->get()
                ];
                return json('Login Successfully.', 200, $data);

            } else {

                $getUser    =   $this->userService->getUser(['email'=>$request->email]);
                if(!$getUser){

                    $getUser   =   User::create([
                        'first_name'    => $request->name,
                        'last_name'     => NULL,
                        'password'      => NULL,
                        'timezone'      => NULL,
                        'coins'         => 100,
                        'game_coins'    => 1000,
                        'email'         => $request->email,
                        'provider'      => NULL,
                        'api_token'     => str_random(60),
                        'status'        => 1,
                        'provider_id'   => $request->password,
                        'signup_from'   => 0
                    ]);

                    $profile    =   Profile::create(['user_id'=>$getUser->id]);

                    $data = [
                        'user_id' => $getUser->id,
                        'api_token' => $getUser->api_token,
                        'game_coins' => $getUser->game_coins,
                        'game_points' => UserGamePoint::select('game_id', 'points')->where(['user_id' => $getUser->id])->get()
                    ];
                    return json('Login Successfully.', 200, $data);

                } else {

                    $this->userService->updateRecord(['id'=>$getUser->id], ['provider'=>$request->password, 'provider_id'=>$request->password]);
                }
            }
        } else {

            if(Auth::attempt($user_credentials)){

                $authUser = auth()->user();
                if (empty($authUser->api_token)) {

                    User::where('id', $authUser->id)
                        ->update([
                            'api_token' => str_random(60),
                            'game_coins' => 1000
                        ]);
                }

                $authUser->refresh();
                $data = [
                    'user_id' => $authUser->id,
                    'api_token' => $authUser->api_token,
                    'game_coins' => $authUser->game_coins,
                    'game_points' => UserGamePoint::select('game_id', 'points')->where(['user_id' => $authUser->id])->get()
                ];
                return json('Login Successfully.', 200,  $data);
            }else{

                return json('Email or password is incorrect.', 400);
            }
        }
    }
}
