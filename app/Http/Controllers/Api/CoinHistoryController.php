<?php

namespace App\Http\Controllers\Api;

use App\CoinHistory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoinHistoryController extends Controller
{
    public function CoinHistory(Request $request){

        $msg = '';
        $code = '';
        $coin_history = CoinHistory::create([
            'user_id' => auth()->id(),
            'game_session' => $request->game_session,
            'game_id' => $request->game_id,
            'topic_id' => $request->topic_id,
            'context_id' => $request->context_id,
            'mode' => $request->mode,
            'type' => $request->type,
            'coins' => $request->coins
        ]);
        if($coin_history){
            $msg = 'Coins history has been added!';
            $code = '200';
        }else{
            $msg = 'Something went wrong!';
            $code = '400';
        }

        $user_session = User::where('id', auth()->id())->first();
        if($user_session){
            $user_session->game_session = $request->game_session;
            $user_session->save();
        }

        return json($msg, $code);
    }
}
