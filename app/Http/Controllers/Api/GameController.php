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

    /**
     *
    @SWG\Post(
     *     path="/update_coins",
     *     description="Update user ame coins",
     *
    @SWG\Parameter(
     *         name="id",
     *         in="formData",
     *         type="string",
     *         description="Enter user id",
     *         required=true,
     *     ),
     *
    @SWG\Parameter(
     *         name="game_coins",
     *         in="formData",
     *         type="string",
     *         description="Enter game_coins",
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
    public function update_coins(Request $request){

        $validate = Validator::make($request->all(), [
            'id' => 'required|integer',
            'game_coins' => 'required|integer'
        ]);

        if ($validate->fails()){
            return json($validate->errors(), 400);
        }

        $coins = User::find($request->id);
        $coins->game_coins = $request->game_coins;
        $updated = $coins->save();
        if($updated){

            return json('User coins are updated.', 200);
        } else{

            return json('Something wrong here!', 400);
        }
    }
}
