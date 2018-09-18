<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/17/18
 * Time: 1:09 PM
 */

namespace App\Repositories;


use App\FamiliarContext;

class FamiliarContextRepo
{
    protected $familiarContext;
    public function __construct()
    {
        $context= new FamiliarContext();
        $this->familiarContext=$context;
    }
    public function create($data){
        return $this->familiarContext->insert($data);
    }
    /**
     * get context of user
     */
    public function getContext($user_id){
        return $this->familiarContext->where('user_id', $user_id)->get();
    }
    /**
     * get delete all context of user
     */
    public function delete($user_id){
        return $this->familiarContext->where('user_id', $user_id)->delete();
    }
    
}