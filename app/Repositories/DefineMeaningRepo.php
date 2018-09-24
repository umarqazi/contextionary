<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/30/18
 * Time: 11:21 AM
 */

namespace App\Repositories;


use App\DefineMeaning;
use App\Http\Controllers\SettingController;
use Auth;
use DB;
use Carbon\Carbon;
use App\Repositories\ContextPhraseRepo;
use Config;

class DefineMeaningRepo
{
    protected $meaning;
    protected $userRepo;
    protected $contextPhrase;
    protected $selected_bids;

    public function __construct()
    {
        $meaning=new DefineMeaning();
        $this->meaning=$meaning;
        $users=new UserRepo();
        $this->userRepo=$users;
        $setting=new SettingController();
        $this->selected_bids=$setting->getKeyValue(env('SELECTED_BIDS'))->values;
    }
    /*
     * save function
     */
    public function create($data){
        $this->meaning->create($data);
        return $this->meaning->latest()->first();
    }
    /*
     * check Meaning against context or phrase
     */
    public function fetchMeaning($context_id, $phrase_id){
        return $this->meaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'user_id'=>Auth::user()->id])->first();
    }
    /*
     * update record
     */
    public function update($data, $meaning_id){
        return $this->meaning->where('id', $meaning_id)->update($data);
    }
    public function contributions($user_id){
        return $this->meaning->where('user_id',$user_id);
    }
    /*
     * fetch total numbers of contributions of user
     */
    public function getUserContributions($user_id){
        return $this->contributions($user_id)->where('coins', '!=', NULL)->count();
    }
    /*
     * get all contributions of user
     */
    public function getAllContributedMeaning(){
       return $this->contributions(Auth::user()->id)->get();
    }
    /*
     * get group of context and phrase
     */
    public function fetchContextPhraseMeaning(){
        return $records=$this->meaning->where('coins','!=', NULL)->leftJoin('bidding_expiry', function ($query){
            $query->on('define_meanings.context_id', '=', 'bidding_expiry.context_id');
            $query->on('define_meanings.phrase_id', '=', 'bidding_expiry.phrase_id');
        })->select('*', DB::raw('count(*) as total'))->groupBy('define_meanings.context_id', 'define_meanings.phrase_id')->get();
    }

    public function getRecords($context_id, $phrase_id){
        return $this->meaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id]);
    }
    /**
     * @param $meaning_id
     * @return bool
     */
    public function checkTotalPhrase($meaning_id){
        $checkContextPhrase='';
        $getContextInfo=$this->meaning->where('id', $meaning_id)->first();
        if($getContextInfo):
            $checkContextPhrase=$this->getRecords($getContextInfo->context_id, $getContextInfo->phrase_id)->where('coins', '!=', NULL)->count();
        endif;
        return $checkContextPhrase;
    }
    /*
     * update status except first 9
     */
    public function updateMeaningStatus($context_id, $phrase_id){

        /**update status for vote of first 9 contributor*/

        $records=$this->getRecords($context_id, $phrase_id)->orderBy('coins', 'desc')->limit($this->selected_bids)->update(['status'=>'1']);

        /** update status for refund of contributor */

        $rejectedUsers=$this->getRecords($context_id, $phrase_id)->where(['status'=>'0'])->update(['status'=>'2']);

        /** update coins in user tables */
        $getUsers=$this->getRecords($context_id, $phrase_id)->where(['status'=>'2'])->get();
        foreach($getUsers as $user):
            if($user['coins']!=NULL):
                $this->userRepo->updateCoins($user['coins'], $user['user_id']);
            endif;
        endforeach;
        return true;
    }
    /**
     * get Meaning for Vote
     */
    public function getAllVoteMeaning($context_id,  $phrase_id){
        return $this->getRecords($context_id, $phrase_id)->where('user_id','!=',Auth::user()->id)->where('status', '1')->get();
    }
    /**
     * update voting status
     */
    public function updateVoteStatus($context_id, $phrase_id){
        return $this->meaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id])->update(['status'=>3]);
    }
    /**
     * get Illustrate Records
     */
    public function illustrates($contexts){
        return $this->meaning->whereIn('context_id', $contexts)->where(['status'=>'3', 'position'=>'1'])->get();
    }
    /**
     * total meaning
     */
    public function totalMeaning($context_id, $phrase_id){
        return $this->meaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id])->count();
    }
    /**
     * get first meaning for illustrator
     */
    public function selectedMeaning($context_id, $phrase_id){
        return $this->meaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'position'=>'1'])->with('users')->first();
    }
}
