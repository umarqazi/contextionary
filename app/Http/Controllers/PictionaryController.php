<?php

namespace App\Http\Controllers;

use App\Pictionary;
use App\PictionaryGame;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use View;
use Redirect;

class PictionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('pictionary_index');
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        $game = PictionaryGame::where('is_complete', 0)->where('user_id', Auth::user()->id)->first();
        $pictionary = Pictionary::inRandomOrder()->get()->first();
        if($game == null){
            $game = new PictionaryGame();
            $game->user_id = Auth::user()->id;
            $game->question_count = $game->question_count + 1;
            $game->save();
            $game = PictionaryGame::find($game->id);
            return View::make('pictionary')->with('pictionary', $pictionary)->with('game',$game);
        }else{
            if($game->question_count < 20) {
                $game->question_count = $game->question_count + 1;
                $game->save();
                return View::make('pictionary')->with('pictionary', $pictionary)->with('game',$game);
            }else{
                $game->is_complete = 1;
                $game->save();
                $high_score = PictionaryGame::where('user_id', Auth::user()->id)->max('score');
                return View::make('pictionary_result')->with('game', $game)->with('high_score', $high_score);
            }
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function verifyAnswer(Request $request){
        $pictionary = Pictionary::find($request->ques_id);
        $game = PictionaryGame::find($request->game_id);
        if($pictionary->answer == $request->option ){
            $game->score = $game->score + 1;
            $game->save();
            return response()->json([
                'http-status' => Response::HTTP_OK,
                'status' => true,
                'body' => null
            ],Response::HTTP_OK);
        }
        else{
            return response()->json([
                'http-status' => Response::HTTP_OK,
                'status' => false,
                'body' => $pictionary->{$pictionary->answer},
            ],Response::HTTP_OK);
        }
    }

    /**
     * @return mixed
     */
    public function reset(){
        $game = PictionaryGame::where('is_complete', 0)->where('user_id', Auth::user()->id)->first();
        $game->is_complete = 1;
        $game->save();
        return View::make('pictionary_index');
    }
}
