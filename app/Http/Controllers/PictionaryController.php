<?php

namespace App\Http\Controllers;

use App\Services\PictionaryGameService;
use App\Services\PictionaryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use View;
use Redirect;

class PictionaryController extends Controller
{

    /**
     * @var PictionaryService
     */
    private $pictionary_service;

    /**
     * @var PictionaryGameService
     */
    private $pictionary_game_service;

    /**
     * PictionaryController constructor.
     */
    public function __construct()
    {
        $pictionary_service = new PictionaryService();
        $this->pictionary_service = $pictionary_service;
        $pictionary_game_service = new PictionaryGameService();
        $this->pictionary_game_service = $pictionary_game_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('user.user_plan.games.pictionary_index');
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        $user_id = Auth::user()->id;
        $game = $this->pictionary_game_service->incompleteUserGame($user_id);
        if($game == null){
            $pictionary = $this->pictionary_service->getRandom([]);
            $game = $this->pictionary_game_service->create($user_id);
            $game = $this->pictionary_game_service->get($game->id);
            if(!empty($pictionary)){
                $this->pictionary_game_service->addQuestion($game->id, $pictionary->id);
                return View::make('user.user_plan.games.pictionary')->with(['pictionary' => $pictionary, 'game' => $game]);
            }
            else{
                return View::make('user.user_plan.games.pictionary_error')->with(['message' => 'No more Questions. Be patient, we are adding more questions.']);
            }
        }else{
            if($game->question_count < 21) {
                $questioned = explode(',', $game->questions);
                $answered   = explode(',', $game->questions_answered);
                $pictionary = $this->pictionary_service->getQuestion($questioned, $answered);
                $game = $this->pictionary_game_service->get($game->id);
                if(!empty($pictionary)){
                    if($questioned == $answered){
                        $this->pictionary_game_service->addQuestion($game->id, $pictionary->id);
                    }
                    return View::make('user.user_plan.games.pictionary')->with(['pictionary' => $pictionary, 'game' => $game]);
                }else{
                    return View::make('user.user_plan.games.pictionary_error')->with(['message' => 'Pictionary is down! Please contact support to report the issue.']);
                }
            }else{
                $this->pictionary_game_service->complete($game->id);
                $game = $this->pictionary_game_service->get($game->id);
                $high_score = $this->pictionary_game_service->getHighScore($user_id);
                return View::make('user.user_plan.games.pictionary_result')->with(['game' => $game, 'high_score' => $high_score]);
            }
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function verifyAnswer(Request $request){
        $pictionary = $this->pictionary_service->find($request->ques_id);
        $game = $this->pictionary_game_service->find($request->game_id);
        if(!empty($pictionary) && !empty($game)){
            $this->pictionary_game_service->addAnsweredQuestion($game->id, $pictionary->id);
            if($pictionary->answer == $request->option ){
                $this->pictionary_game_service->addScoreCount($game->id);
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
        else{
            return response()->json([
                'http-status' => Response::HTTP_OK,
                'status' => false,
                'message' => 'Game or Question not found.',
                'body' => $request->all(),
            ],Response::HTTP_OK);
        }
    }

    /**
     * @return mixed
     */
    public function continue(){
        $game = $this->pictionary_game_service->incompleteUserGame(Auth::user()->id);
        if(!empty($game)){
            $this->pictionary_game_service->addQuestionCount($game->id);
            $lang=lang_url('pictionary');
            return redirect()->to($lang);
        }
        else{
            return View::make('user.user_plan.games.pictionary_error')->with(['message' => 'Pictionary is down! Please contact support to report the issue.']);
        }
    }

    /**
     * @return mixed
     */
    public function reset(){
        $game = $this->pictionary_game_service->incompleteUserGame(Auth::user()->id);
        if(!empty($game)){
            $this->pictionary_game_service->complete($game->id);
            return View::make('user.user_plan.games.pictionary_index');
        }
        else{
            return View::make('user.user_plan.games.pictionary_error')->with(['message' => 'Pictionary is down! Please contact support to report the issue.']);
        }
    }
}