<?php

namespace App\Http\Controllers;

use App\Services\SpotTheIntruderGameService;
use App\Services\SpotTheIntruderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use View;
use Redirect;

class SpotIntruderController extends Controller
{

    /**
     * @var SpotTheIntruderService
     */
    private $spot_the_intruder_service;

    /**
     * @var SpotTheIntruderGameService
     */
    private $spot_the_intruder_game_service;

    /**
     * SpotIntruderController constructor.
     */
    public function __construct()
    {
        $spot_the_intruder_service = new SpotTheIntruderService();
        $this->spot_the_intruder_service = $spot_the_intruder_service;
        $spot_the_intruder_game_service = new SpotTheIntruderGameService();
        $this->spot_the_intruder_game_service = $spot_the_intruder_game_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('user.user_plan.games.spot_intruder_index');
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        $user_id = Auth::user()->id;
        $game = $this->spot_the_intruder_game_service->incompleteUserGame($user_id);
        if($game == null){
            $question = $this->spot_the_intruder_service->getRandom([]);
            $game = $this->spot_the_intruder_game_service->create($user_id);
            $this->spot_the_intruder_game_service->addQuestion($game->id, $question->id);
            $game = $this->spot_the_intruder_game_service->get($game->id);
            return View::make('user.user_plan.games.spot_intruder')->with('question', $question)->with('game',$game);
        }else{
            if($game->question_count < 20) {
                $question = $this->spot_the_intruder_service->getRandom(explode(',', $game->questions));
                $game = $this->spot_the_intruder_game_service->addQuestionCount($game->id);
                $game = $this->spot_the_intruder_game_service->addQuestion($game->id, $question->id);
                $game = $this->spot_the_intruder_game_service->get($game->id);
                return View::make('user.user_plan.games.spot_intruder')->with('question', $question)->with('game',$game);
            }else{
                $game = $this->spot_the_intruder_game_service->complete($game->id);
                $game = $this->spot_the_intruder_game_service->get($game->id);
                $high_score = $this->spot_the_intruder_game_service->getHighScore($user_id);
                return View::make('user.user_plan.games.spot_intruder_result')->with('game', $game)->with('high_score', $high_score);
            }
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function verifyAnswer(Request $request){
        $question = $this->spot_the_intruder_service->find($request->ques_id);
        $game = $this->spot_the_intruder_game_service->find($request->game_id);
        if($question->answer == $request->option ){
            $this->spot_the_intruder_game_service->addScoreCount($game->id);
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
        $game = $this->spot_the_intruder_game_service->incompleteUserGame(Auth::user()->id);
        $this->spot_the_intruder_game_service->complete($game->id);
        return View::make('user.user_plan.games.spot_intruder_index');
    }
}
