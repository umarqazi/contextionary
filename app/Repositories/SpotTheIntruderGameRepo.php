<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Repositories;

use App\SpotIntruderGame;

class SpotTheIntruderGameRepo extends BaseRepo implements IRepo
{
    /**
     * @var SpotIntruderGame
     */
    private $spot_intruder_game;

    /**
     * SpotTheIntruderGameRepo constructor.
     */
    public function __construct()
    {
        $spot_intruder_game = new SpotIntruderGame();
        $this->spot_intruder_game = $spot_intruder_game;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function incompleteUserGame($user_id){
        return $this->spot_intruder_game->incompleteUserGame($user_id);
    }

    /**
     * @param $user_id
     * @return SpotIntruderGame
     */
    public function create($user_id){
        $game = new SpotIntruderGame();
        $game->user_id = $user_id;
        $game->question_count = 1;
        $game->save();
        return $game;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return $this->find($id);
    }

    /**
     * @param $game_id
     * @return mixed
     */
    public function addQuestionCount($game_id)
    {
        $game = $this->spot_intruder_game->find($game_id);
        $game->question_count = $game->question_count + 1;
        $game->save();
        return $game;
    }

    /**
     * @param $game_id
     * @return mixed
     */
    public function addScoreCount($game_id)
    {
        $game = $this->spot_intruder_game->find($game_id);
        $game->score = $game->score + 1;
        $game->save();
        return $game;
    }

    /**
     * @param $game_id
     * @return mixed
     */
    public function addQuestion($game_id, $ques_id)
    {
        $game = $this->find($game_id);
        if($game->questions){
            $game->questions = $game->questions.','.$ques_id;
        }else{
            $game->questions = $ques_id;
        }
        $game->save();
        return $game;
    }

    /**
     * @param $game_id
     * @return mixed
     */
    public function complete($game_id)
    {
        $game = $this->find($game_id);
        $game->is_complete = 1;
        $game->save();
        return $game;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->spot_intruder_game->find($id);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getHighScore($user_id){
        return $this->spot_intruder_game->getHighScore($user_id);
    }
}