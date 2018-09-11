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
use DB;

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
     * get votes of meaning
     */
    public function getVotesMeaning($context_id, $phrase_id){
        return $this->voteMeaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id]);
    }

    /**
     * check login user vote
     */
    public function checkUserVote($data){
        return $this->getVotesMeaning($data['context_id'], $data['phrase_id'])->where('user_id', Auth::user()->id)->first();
    }
    /**
     * total number of vote
     */
    public function totalVotes($context_id, $phrase_id){
        return $total=$this->getVotesMeaning($context_id, $phrase_id)->get()->count();
    }
    /**
     * get highest votes
     */
    public function hightVotes($context_id, $phrase_id){
        return $total=$this->getVotesMeaning($context_id, $phrase_id)->with('meaning')->where('vote', 1)->groupBy('define_meaning_id')->select('vote_meanings.*', DB::raw('count(*) as total'))->limit(3)->orderBy('total', 'DESC')->get();
    }
}