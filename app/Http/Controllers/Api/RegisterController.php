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
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        if($validate->fails()){
            return response()->json(['message', $validate->errors()], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'coins' => 100,
            'status' => 1,
            'signup_from' => 0
        ]);

        if($user){

            return response()->json(['message', 'You have successfully registered.'], 200);
        } else{

            return response()->json(['message', 'Something wrong'], 500);
        }
    }
}
