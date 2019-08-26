<?php

namespace App\Http\Controllers\Api;

use App\UserCurrentContext;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserCurrentContextController extends Controller
{
    public function CurrentContext(Request $request){

        $pre_context_phrase = UserCurrentContext::where('user_id', auth()->id())->first();
        
        if($pre_context_phrase){
            
            $pre_context_phrase->current_context_id = $request->context_id;
            $pre_context_phrase->last_phrase_id = $request->phrase_id;
            $updated = $pre_context_phrase->save();
            if($updated){

                return json('User context has been updated', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }else{

            $validate = Validator::make($request->all(), [
                'context_id' => 'required|integer',
                'phrase_id' => 'required|integer'
            ]);
            if($validate->fails()){

                return json($validate->messages()->first(), 400);
            }
            $current_context_phrase = UserCurrentContext::create([
                'user_id' => auth()->id(),
                'current_context_id' => $request->context_id,
                'last_phrase_id' => $request->phrase_id,
            ]);

            if($current_context_phrase){

                return json('Data added successfully', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }
    }
}
