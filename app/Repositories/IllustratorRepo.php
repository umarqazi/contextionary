<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/13/18
 * Time: 11:46 AM
 */

namespace App\Repositories;


use App\Illustrator;

class IllustratorRepo
{
    protected $illustrator;

    public function __construct()
    {
        $illustrate=new Illustrator();
        $this->illustrator=$illustrate;
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
    public function currentUserIllustrate($context_id, $phrase_id, $user_id){
        $check=['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'user_id'=>$user_id];
        return $this->phraseIllustrate($check)->first();
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
}