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
    protected $setting;

    /**
     * DefineMeaningRepo constructor.
     */
    public function __construct()
    {
        $meaning=new DefineMeaning();
        $this->meaning=$meaning;
        $users=new UserRepo();
        $this->userRepo=$users;
        $this->setting=new SettingController();
    }

    /**
     * @param $data
     * @return mixed
     * save function
     */
    public function create($data){
        $this->meaning->create($data);
        return $this->meaning->latest()->first();
    }
    /*
     * check Meaning against context or phrase
     */
    public function fetchUserRecord($data){
        return $this->meaning->where($data)->with('users')->first();
    }

    /**
     * @param $data
     * @param $meaning_id
     * @return mixed
     * update record
     */
    public function update($data, $meaning_id){
        return $this->meaning->where('id', $meaning_id)->update($data);
    }

    /**
     * @param $user_id
     * @return mixed
     * fetch total numbers of contributions of user
     */
    public function getUserContributions($user_id){
        $data=['user_id'=>$user_id];
        return $this->meaning->where($data)->where('coins', '!=', NULL)->count();
    }

    /**
     * @param $data
     * @return mixed
     * get all contributions of user
     */
    public function getAllContributedMeaning($data){
       return $this->meaning->where($data)->get();
    }

    /**
     * @return mixed
     * get group of context and phrase
     */
    public function fetchContextPhraseMeaning(){
        return $records=$this->meaning->where('coins','!=', NULL)->leftJoin('bidding_expiry', function ($query){
            $query->on('define_meanings.context_id', '=', 'bidding_expiry.context_id');
            $query->on('define_meanings.phrase_id', '=', 'bidding_expiry.phrase_id');
        })->select('*', DB::raw('count(*) as total'))->groupBy('define_meanings.context_id', 'define_meanings.phrase_id')->get();
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     */
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

    /**
     * @param $context_id
     * @param $phrase_id
     * @return bool
     * update status except first 9
     */
    public function updateMeaningStatus($context_id, $phrase_id){
        $this->selected_bids=$this->setting->getKeyValue(env('SELECTED_BIDS'))->values;
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
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * get Meaning for Vote
     */
    public function getAllVoteMeaning($context_id,  $phrase_id){
        return $this->getRecords($context_id, $phrase_id)->where('user_id','!=',Auth::user()->id)->where('status', '1')->get();
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * update voting status
     */
    public function updateVoteStatus($context_id, $phrase_id){
        return $this->meaning->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'status'=>'1'])->update(['status'=>3]);
    }

    /**
     * @return mixed
     * get Illustrate Records
     */
    public function illustrates(){
        return $this->meaning->where(['status'=>'3', 'position'=>'1'])->get();
    }

    /**
     * @param $data
     * @return mixed
     * total meaning
     */
    public function totalRecords($data){
        return $this->meaning->where($data)->count();
    }
}
