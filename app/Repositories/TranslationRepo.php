<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 10/2/18
 * Time: 11:53 AM
 */

namespace App\Repositories;


use App\Http\Controllers\SettingController;
use App\Translation;
use App\Repositories\UserRepo;
use Auth;

class TranslationRepo
{
    protected $translation;
    protected $userRepo;

    /**
     * IllustratorRepo constructor.
     */
    public function __construct()
    {
        $this->translation      =   new Translation();
        $this->userRepo         =   new UserRepo();
        $setting                =   new SettingController();
        $this->selected_bids    =   $setting->getKeyValue(env('SELECTED_BIDS'))->values;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){
        return $this->translation->create($data);
    }

    /**
     * @param $data
     * @param $illustrator_id
     * @return mixed
     */
    public function update($data, $illustrator_id){
        return $this->translation->where('id', $illustrator_id)->update($data);
    }

    /**
     * @param $data
     * @return mixed
     * total meaning
     */
    public function totalRecords($data){
        return $this->translation->where($data)->count();
    }

    /**
     * @param $data
     * @return mixed
     * check Translation against context or phrase
     */
    public function fetchUserRecord($data){
        return $this->translation->where($data)->with('users')->first();
    }

    /**
     * @param $meaning_id
     * @return bool
     */
    public function checkTotalPhrase($meaning_id){
        $checkContextPhrase='';
        $getContextInfo=$this->translation->where('id', $meaning_id)->first();
        if($getContextInfo):
            $data=['context_id'=>$getContextInfo->context_id, 'phrase_id'=>$getContextInfo->phrase_id];
            $checkContextPhrase=$this->translation->where($data)->where('coins', '!=', NULL)->count();
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

        /**update status for vote of first 9 contributor*/
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id];
        $records=$this->translation->where($data)->orderBy('coins', 'desc')->limit($this->selected_bids)->update(['status'=>'1']);

        /** update status for refund of contributor */

        $rejectedUsers=$this->translation->where($data)->where(['status'=>'0'])->update(['status'=>'2']);

        /** update coins in user tables */
        $getUsers=$this->translation->where($data)->where(['status'=>'2'])->get();
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
     * get translations for Vote
     */
    public function getAllVoteTranslators($context_id,  $phrase_id){
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'status'=>'1'];
        return $this->translation->where($data)->where('user_id','!=',Auth::user()->id)->get();
    }

    /**
     * @param $user_id
     * @return mixed
     * fetch total numbers of contributions of user
     */
    public function getUserContributions($user_id){
        $data=['user_id'=>$user_id];
        return $this->translation->where($data)->where('coins', '!=', NULL)->count();
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * update voting status
     */
    public function updateVoteStatus($context_id, $phrase_id){
        return $this->translation->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'status'=>'1'])->update(['status'=>3]);
    }
}