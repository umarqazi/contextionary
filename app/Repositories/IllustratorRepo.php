<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/13/18
 * Time: 11:46 AM
 */

namespace App\Repositories;


use App\Http\Controllers\SettingController;
use App\Illustrator;
use Config;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;

class IllustratorRepo
{
    protected $illustrator;
    protected $userRepo;
    protected $selected_bids;

    /**
     * IllustratorRepo constructor.
     */
    public function __construct()
    {
        $illustrate=new Illustrator();
        $userRepo=new UserRepo();
        $this->illustrator=$illustrate;
        $this->userRepo=$userRepo;
        $setting=new SettingController();
        $this->selected_bids=$setting->getKeyValue(env('SELECTED_BIDS'))->values;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){
        return $this->illustrator->create($data);
    }

    /**
     * @param $data
     * @param $illustrator_id
     * @return mixed
     */
    public function update($data, $illustrator_id){
        return $this->illustrator->where('id', $illustrator_id)->update($data);
    }

    /**
     * @param $user_id
     * @return mixed
     * fetch total numbers of contributions of user
     */
    public function getUserContributions($user_id){
        $data=['user_id'=>$user_id];
        return $this->illustrator->where($data)->where('coins', '!=', NULL)->count();
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * update voting status
     */
    public function updateVoteStatus($context_id, $phrase_id){
        return $this->illustrator->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'status'=>'1'])->update(['status'=>3]);
    }

    /**
     * @param $meaning_id
     * @return bool
     */
    public function checkTotalPhrase($meaning_id){
        $checkContextPhrase='';
        $getContextInfo=$this->illustrator->where('id', $meaning_id)->first();
        if($getContextInfo):
            $data=['context_id'=>$getContextInfo->context_id, 'phrase_id'=>$getContextInfo->phrase_id];
            $checkContextPhrase=$this->illustrator->where($data)->where('coins', '!=', NULL)->count();
        endif;
        return $checkContextPhrase;
    }

    /**
     * @param $data
     * @return mixed
     * total meaning
     */
    public function totalRecords($data){
        return $this->illustrator->where($data)->count();
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
        $records=$this->illustrator->where($data)->limit($this->selected_bids)->orderBy('coins', 'desc')->update(['status'=>'1']);

        /** update status for refund of contributor */

        $rejectedUsers=$this->illustrator->where($data)->where(['status'=>'0'])->update(['status'=>'2']);

        /** update coins in user tables */
        $getUsers=$this->illustrator->where($data)->where(['status'=>'2'])->get();
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
     * get illustrators for Vote
     */
    public function getAllVoteIllustrators($context_id,  $phrase_id){
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'status'=>'1'];
        return $this->illustrator->where($data)->where('user_id','!=',Auth::user()->id)->get();
    }

    /**
     * @param $data
     * @return mixed
     * check Meaning against context or phrase
     */
    public function fetchUserRecord($data){
        return $this->illustrator->where($data)->with('users')->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function selectedIllustrates($data){
        return $this->illustrator->where($data)->with('users')->get();
    }
}