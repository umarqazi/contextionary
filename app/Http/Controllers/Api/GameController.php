<?php

namespace App\Http\Controllers\Api;

use App\Game;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     *
    @SWG\Post(
     *     path="/games",
     *     description="Games List",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    public function games(){
        $games = Game::all();
        if($games){
            return json('Games are shown as:', 200, $games);
        } else{
            return json('Data not found!', 400);
        }
    }

    public function UpdateUserInfo(Request $request){

        $update_info = User::where('id', auth()->id())->first();
        if($update_info){

            if(isset($request->game_coins)){

                $update_info->game_coins = $request->game_coins;
            }
            if(isset($request->aladdin_lamp)){

                $update_info->aladdin_lamp = $request->aladdin_lamp;
            }
            if(isset($request->butterfly_effect)){

                $update_info->butterfly_effect = $request->butterfly_effect;
            }
            if(isset($request->stopwatch)){

                $update_info->stopwatch = $request->stopwatch;
            }
            if(isset($request->time_traveller)){

                $update_info->time_traveller = $request->time_traveller;
            }
            $updated = $update_info->save();
            if($updated){
                return json('User info updated', 200);
            }else{
                return json('Something went wrong!', 400);
            }
        }
    }
}
