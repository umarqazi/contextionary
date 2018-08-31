<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/30/18
 * Time: 11:21 AM
 */

namespace App\Repositories;


use App\DefineMeaning;
use Auth;

class DefineMeaningRepo
{
    protected $message;
    public function __construct(DefineMeaning $meaning)
    {
        $this->meaning=$meaning;
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
        return $this->meaning->where('user_id',$user_id)->where('bid', '!=', NULL);
    }
    /*
     * fetch total numbers of contributions of user
     */
    public function getUserContributions($user_id){
        return $this->contributions($user_id)->count();
    }
    /*
     * get all contributions of user
     */
    public function getAllContributedMeaning(){
       return $this->contributions(Auth::user()->id)->get();
    }
}