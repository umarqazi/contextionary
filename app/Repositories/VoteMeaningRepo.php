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

    /**
     * VoteMeaningRepo constructor.
     */
    public function __construct()
    {
        $vote=new VoteMeaning();
        $this->voteMeaning=$vote;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){
        return $this->voteMeaning->create($data);
    }

    /**
     * @param $data
     * @return mixed
     * get votes of meaning
     */
    public function getVotesMeaning($data){
        return $this->voteMeaning->where($data);
    }

    /**
     * @param $data
     * @param $key
     * @return mixed
     * check login user vote
     */
    public function checkUserVote($data, $key){
        return $this->getVotesMeaning($data)->where('type', '=', $key)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function totalVotes($data){
        return $total=$this->getVotesMeaning($data)->get()->count();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function votesPerDefineMe($data){
        return $total=$this->getVotesMeaning($data)->get()->count();
    }

    /**
     * @param $checkArray
     * @return mixed
     */
    public function hightVotes($checkArray){
        $data=['context_id'=>$checkArray['context_id'], 'phrase_id'=>$checkArray['phrase_id'], 'type'=>$checkArray['type']];
        return $total=$this->getVotesMeaning($data)->with($checkArray['type'])->where('vote', 1)->groupBy($checkArray['columnKey'])->select('vote_meanings.*', DB::raw('count(*) as total'))->limit(3)->orderBy('total', 'DESC')->get();
    }

    public function getWinnerVotes($data){
        return $this->getVotesMeaning($data)->with('user')->get();
    }
}