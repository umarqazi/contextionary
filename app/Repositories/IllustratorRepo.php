<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/13/18
 * Time: 11:46 AM
 */

namespace App\Repositories;


use App\Illustrator;
use Config;
use App\Repositories\UserRepo;

class IllustratorRepo
{
    protected $illustrator;
    protected $userRepo;

    public function __construct()
    {
        $illustrate=new Illustrator();
        $userRepo=new UserRepo();
        $this->illustrator=$illustrate;
        $this->userRepo=$userRepo;
    }
    /**
     * create record
     */
    public function create($data){
        return $this->illustrator->create($data);
    }
    /**
     * get phrase illustrator
     */
    public function phraseIllustrate($data){
        return $this->illustrator->where($data);
    }
    /**
     * get login user illustrator
     */
    public function currentUserIllustrate($data){
        return $this->phraseIllustrate($data)->first();
    }
    /*
     * update record
     */
    public function update($data, $meaning_id){
        return $this->illustrator->where('id', $meaning_id)->update($data);
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
            $checkContextPhrase=$this->phraseIllustrate($data)->where('coins', '!=', NULL)->count();
        endif;
        return $checkContextPhrase;
    }
    /**
     * total meaning
     */
    public function totalMeaning($context_id, $phrase_id){
        return $this->illustrator->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id])->count();
    }
    /*
     * update status except first 9
     */
    public function updateMeaningStatus($context_id, $phrase_id){

        /**update status for vote of first 9 contributor*/
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id];
        $records=$this->phraseIllustrate($data)->limit(Config::get('constant.selected_bids'))->update(['status'=>'1']);

        /** update status for refund of contributor */

        $rejectedUsers=$this->phraseIllustrate($data)->where(['status'=>'0'])->update(['status'=>'2']);

        /** update coins in user tables */
        $getUsers=$this->phraseIllustrate($data)->where(['status'=>'2'])->get();
        foreach($getUsers as $user):
            if($user['coins']!=NULL):
                $this->userRepo->updateCoins($user['coins'], $user['user_id']);
            endif;
        endforeach;
        return true;
    }
}