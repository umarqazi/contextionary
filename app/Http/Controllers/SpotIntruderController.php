<?php

namespace App\Http\Controllers;

use App\SpotIntruder;
use App\SpotIntruderGame;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use View;
use Redirect;

class SpotIntruderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('spot_intruder_index');
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        $game = SpotIntruderGame::where('is_complete', 0)->where('user_id', Auth::user()->id)->first();
        $question = SpotIntruder::inRandomOrder()->get()->first();
        if($game == null){
            $game = new SpotIntruderGame();
            $game->user_id = Auth::user()->id;
            $game->question_count = $game->question_count + 1;
            $game->save();
            $game = SpotIntruderGame::find($game->id);
            return View::make('spot_intruder')->with('question', $question)->with('game',$game);
        }else{
            if($game->question_count < 20) {
                $game->question_count = $game->question_count + 1;
                $game->save();
                return View::make('spot_intruder')->with('question', $question)->with('game',$game);
            }else{
                $game->is_complete = 1;
                $game->save();
                $high_score = SpotIntruderGame::where('user_id', Auth::user()->id)->max('score');
                return View::make('spot_intruder_result')->with('game', $game)->with('high_score', $high_score);
            }
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function verifyAnswer(Request $request){
        $question = SpotIntruder::find($request->ques_id);
        $game = SpotIntruderGame::find($request->game_id);
        if($question->answer == $request->option ){
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
                'body' => $question->{$question->answer},
            ],Response::HTTP_OK);
        }
    }

    /**
     * @return mixed
     */
    public function reset(){
        $game = SpotIntruderGame::where('is_complete', 0)->where('user_id', Auth::user()->id)->first();
        $game->is_complete = 1;
        $game->save();
        return View::make('spot_intruder_index');
    }
}
