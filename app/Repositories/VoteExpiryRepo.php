<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 2:53 PM
 */

namespace App\Repositories;


use App\VoteExpiry;
use App\VoteMeaning;
use Carbon\Carbon;
use Auth;

class VoteExpiryRepo
{
    protected $voteExpiry;
    protected $voteMeaning;

    /**
     * VoteExpiryRepo constructor.
     */
    public function __construct()
    {
        $expiry=new VoteExpiry();
        $this->voteExpiry=$expiry;
        $vote=new VoteMeaning();
        $this->voteMeaning=$vote;
    }

    /**
     * @param $data
     * save vote Expiry
     */
    public function create($data){
        $this->voteExpiry->create($data);
    }

    /**
     * @param $data
     * @return mixed
     * check previous records
     */
    public function checkRecords($data){
        return $this->voteExpiry->where($data)->first();
    }

    /**
     * @param $type
     * @return mixed
     */
    public function votes($type){
        return $this->voteExpiry->where(['vote_type'=>$type, 'status'=>'0'])->select('vote_expiries.*');
    }
    /**
     * get latest pharse for vote
     *
     * table # 1: vote_expiry
     * table # 2: define_meaning RELATION :
     * table # 3: vote_meaning
     */
    public function getPhraseMeaning($type, $data){
        return $this->voteExpiry->where('vote_type', $type)->whereDate('expiry_date', '>=', Carbon::today())
            ->leftJoin('vote_meanings', function ($query){
                $query->on('vote_expiries.context_id', '=', 'vote_meanings.context_id');
                $query->on('vote_expiries.phrase_id', '=', 'vote_meanings.phrase_id')->where('vote_meanings.user_id', Auth::user()->id);

            })->select('vote_expiries.*')
            ->where('vote_meanings.user_id', '=', NULL)
            ->orderby('vote_expiries.id', 'ASC')->first();
    }

    /**
     * @param $type
     * @param $contexts
     * @return mixed
     * get all votes
     */
    public function getAllVotes($type, $contexts){
        return $this->votes($type)->whereIn('context_id', $contexts)->get();
    }

    /**
     * @param $type
     * @return mixed
     * get expired votes
     */
    public function getAllMeaningVotes($type){
        return $this->voteExpiry->where('vote_type', $type)->where('status', 0)->get();
    }

    /**
     * @param $id
     * @param $records
     * @return mixed
     * update records status
     */
    public function updateStatus($id, $records){
        return $this->voteExpiry->where('id', $id)->update($records);
    }
}