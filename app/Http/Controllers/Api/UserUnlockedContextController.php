<?php

namespace App\Http\Controllers\Api;

use App\UserUnlockedContext;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserUnlockedContextController extends Controller
{
    public function UnlockedContext(Request $request){

        $unlocked_context = UserUnlockedContext::where('user_id', auth()->id())->first();
        if($unlocked_context){

            $unlocked_context->unlocked_context = $request->unlocked_context;
            $updated = $unlocked_context->save();
            if($updated){

                return json('User unlocked context are updated', 200);
            } else{

                return json('Something wrong here!', 400);
            }
        } else {

            $validate = Validator::make($request->all(), [
                'unlocked_context' => 'required|integer'
            ]);
            if ($validate->fails()) {

                return json($validate->errors(), 400);
            } else{

                $user_unlocked_context = UserUnlockedContext::create([
                    'user_id' => auth()->id(),
                    'unlocked_context' => $request->unlocked_context
                ]);

                if($user_unlocked_context){

                    return json('User unlocked context has been saved successfully', 200);
                } else {

                    return json('Something wrong here!', 400);
                }
            }
        }
    }
}
