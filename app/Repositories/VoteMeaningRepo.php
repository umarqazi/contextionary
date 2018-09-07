<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 4:29 PM
 */

namespace App\Repositories;

use App\VoteMeaning;
use Auth;

class VoteMeaningRepo
{
    protected $voteMeaning;

    public function __construct()
    {
        $vote=new VoteMeaning();
        $this->voteMeaning=$vote;
    }

    public function create($data){
        return $this->voteMeaning->create($data);
    }

    /**
     * check login user vote
     */
    public function checkUserVote($data){
        return $this->voteMeaning->where(['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'], 'user_id'=>Auth::user()->id])->first();
    }
}