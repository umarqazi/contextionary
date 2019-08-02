<?php

namespace App\Http\Controllers\Api;

use App\UserGamePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserGamePointController extends Controller
{
    public function user_game_points(Request $request){

        $update_game_points = UserGamePoint::where(['user_id' => $request->user_id, 'game_id' => $request->game_id])->first();
        if($update_game_points){

            $update_game_points->points = $request->points;
            $update_game_points->level = $request->level;
            $updated = $update_game_points->save();
            if($updated){

                return json('User points are updated', 200);
            } else{

                return json('Something wrong here!', 400);
            }
        } else{

            $validate = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'game_id' => 'required|integer',
                'points' => 'required|integer',
            ]);
            if($validate->fails()){

                return json($validate->errors(), 400);
            }

            $user_game_points = UserGamePoint::create([
                'user_id' => $request->user_id,
                'game_id' => $request->game_id,
                'points' => $request->points,
                'level' => $request->level ?? null
            ]);
            if($user_game_points){

                return json('User score has been saved successfully', 200);
            } else {

                return json('Something wrong here!', 400);
            }
        }
    }
}
