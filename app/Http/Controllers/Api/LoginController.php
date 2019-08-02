<?php

namespace App\Http\Controllers\Api;

use App\UserGamePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
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

        if(Auth::attempt($user_credentials)){
            $data = [
                'user_id' => auth()->user()->id,
                'api_token' => auth()->user()->api_token,
                'game_coins' => auth()->user()->game_coins,
                'game_points' => UserGamePoint::select('game_id', 'points')->where(['user_id' => auth()->user()->id])->get()
            ];
            return json('Login Successfully.', 200,  $data);
        }else{

            return json('Email or password is incorrect.', 400);
        }
    }
}
