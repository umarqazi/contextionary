<?php

namespace App\Http\Controllers\Api;

use App\UserAttemptedQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserAttemptedController extends Controller
{
    public function user_attempted_questions(Request $request){

        $validate = Validator::make($request->all(), [
            'game_id' => 'required|integer'
        ]);
        if($validate->fails()){

            return json($validate->errors(), 400);
        }

        foreach ($request->attempted_id as $questions_id){

            $attempt_questions_id[] = [
                'user_id' => auth()->id(),
                'game_id' => $request->game_id,
                'question_id' => $questions_id
            ];
        }

        $user_game_points = UserAttemptedQuestion::insert($attempt_questions_id);
        if($user_game_points){

            return json('User questions id have been saved successfully', 200);
        } else {

            return json('Something wrong here!', 400);
        }
    }
}
