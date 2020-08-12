<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    /**
     *
    @SWG\Post(
     *     path="/register",
     *     description="User Signup here",
     *
    @SWG\Parameter(
     *         name="first_name",
     *         in="formData",
     *         type="string",
     *         description="Enter first name",
     *         required=true,
     *     ),
     *
    @SWG\Parameter(
     *         name="last_name",
     *         in="formData",
     *         type="string",
     *         description="Enter last name",
     *         required=true,
     *     ),
     *
    @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="Your email",
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
    @SWG\Parameter(
     *         name="password_confirmation",
     *         in="formData",
     *         type="string",
     *         description="Re-enter your password",
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

    function register(Request $request){

        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6'
        ]);

        if($validate->fails()){
            return json($validate->messages()->first(), 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'api_token' => str_random(60),
            'coins' => 100,
            'game_coins' => 1000,
            'coins_earned' => 1000,
            'status' => 1,
            'signup_from' => 0
        ]);

        if($user){
            $data = [
                'user_id' => $user->id,
                'api_token' => $user->api_token,
                'game_coins' => $user->game_coins,
                'coins_earned' => $user->coins_earned
            ];
            return json('You have successfully registered.', 200, $data);
        } else{

            return json('Something wrong', 400);
        }
    }
}
