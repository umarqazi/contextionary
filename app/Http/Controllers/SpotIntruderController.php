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
            $game = $this->spot_the_intruder_game_service->get($game->id);
            if(!empty($question)){
                $this->spot_the_intruder_game_service->addQuestion($game->id, $question->id);
                return View::make('user.user_plan.games.spot_intruder')->with(['question' => $question, 'game' => $game]);
            }
            else{
                return View::make('user.user_plan.games.spot_intruder_error')->with(['message' => 'No more Questions. Be patient, we are adding more questions.']);
            }
        }else{
            if($game->question_count < 21) {
                $questioned = explode(',', $game->questions);
                $answered   = explode(',', $game->questions_answered);
                $question = $this->spot_the_intruder_service->getQuestion($questioned, $answered);
                $game = $this->spot_the_intruder_game_service->get($game->id);
                if(!empty($question)){
                    if($questioned == $answered) {
                        $this->spot_the_intruder_game_service->addQuestion($game->id, $question->id);
                    }
                    return View::make('user.user_plan.games.spot_intruder')->with(['question' => $question, 'game' => $game]);
                }else{
                    return View::make('user.user_plan.games.spot_intruder_error')->with(['message' => 'Spot The Intruder is down! Please contact support to report the issue.']);
                }
            }else{
                $this->spot_the_intruder_game_service->complete($game->id);
                $game = $this->spot_the_intruder_game_service->get($game->id);
                $high_score = $this->spot_the_intruder_game_service->getHighScore($user_id);
                return View::make('user.user_plan.games.spot_intruder_result')->with(['game' => $game, 'high_score' => $high_score]);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyAnswer(Request $request){
        $question = $this->spot_the_intruder_service->find($request->ques_id);
        $game = $this->spot_the_intruder_game_service->find($request->game_id);
        if(!empty($question) && !empty($game)) {
            $this->spot_the_intruder_game_service->addAnsweredQuestion($game->id, $question->id);
            if ($question->answer == $request->option) {
                $this->spot_the_intruder_game_service->addScoreCount($game->id);
                return response()->json([
                    'http-status' => Response::HTTP_OK,
                    'status' => true,
                    'body' => null
                ], Response::HTTP_OK);
            }
            else {
                return response()->json([
                    'http-status' => Response::HTTP_OK,
                    'status' => false,
                    'body' => $question->{$question->answer},
                ], Response::HTTP_OK);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function continue(){
        $game = $this->spot_the_intruder_game_service->incompleteUserGame(Auth::user()->id);
        if(!empty($game)) {
            $this->spot_the_intruder_game_service->addQuestionCount($game->id);
            $lang = lang_url('intruder');
            return redirect()->to($lang);
        }
        else{
            return View::make('user.user_plan.games.spot_intruder_error')->with(['message' => 'Spot The Intruder is down! Please contact support to report the issue.']);
        }
    }

    /**
     * @return mixed
     */
    public function reset(){
        $game = $this->spot_the_intruder_game_service->incompleteUserGame(Auth::user()->id);
        if(!empty($game)){
            $this->spot_the_intruder_game_service->complete($game->id);
            return View::make('user.user_plan.games.spot_intruder_index');
        }
        else{
            return View::make('user.user_plan.games.spot_intruder_error')->with(['message' => 'Spot The Intruder is down! Please contact support to report the issue.']);
        }
    }
}