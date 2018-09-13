<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Repositories;
use App\Pictionary;
use App\PictionaryGame;

class PictionaryGameRepo extends BaseRepo implements IRepo
{
    /**
     * @var PictionaryGame
     */
    private $pictionary_game;

    /**
     * PictionaryGameRepo constructor.
     */
    public function __construct()
    {
        $pictionary_game = new PictionaryGame();
        $this->pictionary_game = $pictionary_game;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function incompleteUserGame($user_id){
        return $this->pictionary_game->incompleteUserGame($user_id);
    }

    /**
     * @param $user_id
     * @return PictionaryGame
     */
    public function create($user_id){
        $game = new PictionaryGame();
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
        $game = $this->pictionary_game->find($game_id);
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
        $game = $this->pictionary_game->find($game_id);
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
     * @param $ques_id
     * @return mixed
     */
    public function addAnsweredQuestion($game_id, $ques_id)
    {
        $game = $this->find($game_id);
        if($game->questions_answered){
            $game->questions_answered = $game->questions_answered.','.$ques_id;
        }else{
            $game->questions_answered = $ques_id;
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
        return $this->pictionary_game->find($id);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getHighScore($user_id){
        return $this->pictionary_game->getHighScore($user_id);
    }
}