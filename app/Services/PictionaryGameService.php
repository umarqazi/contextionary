<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;

use App\Repositories\PictionaryGameRepo;

class PictionaryGameService extends BaseService implements IService
{

    /**
     * @var PictionaryRepo
     */
    private $pictionary_game_repo;

    /**
     * PictionaryService constructor.
     */
    public function __construct()
    {
        $pictionary_game_repo = new PictionaryGameRepo();
        $this->pictionary_game_repo = $pictionary_game_repo;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function incompleteUserGame($user_id){
        return $this->pictionary_game_repo->incompleteUserGame($user_id);
    }

    /**
     * @param $user_id
     * @return \App\PictionaryGame
     */
    public function create($user_id){
        return $this->pictionary_game_repo->create($user_id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return $this->pictionary_game_repo->get($id);
    }

    /**
     * @param $game_id
     * @return mixed
     */
    public function addQuestionCount($game_id){
        return $this->pictionary_game_repo->addQuestionCount($game_id);
    }

    /**
     * @param $game_id
     * @return mixed
     */
    public function addScoreCount($game_id){
        return $this->pictionary_game_repo->addScoreCount($game_id);
    }

    /**
     * @param $game_id
     * @param $ques_id
     * @return mixed
     */
    public function addQuestion($game_id, $ques_id){
        return $this->pictionary_game_repo->addQuestion($game_id, $ques_id);
    }

    /**
     * @param $game_id
     * @return string
     */
    public function complete($game_id){
        return $this->pictionary_game_repo->complete($game_id);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getHighScore($user_id){
        return $this->pictionary_game_repo->getHighScore($user_id);
    }

    public function find($id){
        return $this->pictionary_game_repo->find($id);
    }
}