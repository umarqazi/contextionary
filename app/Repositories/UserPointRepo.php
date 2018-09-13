<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/10/18
 * Time: 4:59 PM
 */

namespace App\Repositories;


use App\UserPoint;
use Auth;

class UserPointRepo
{
    /**
     * @var string
     */
    /**
     *
     */
    private $userPoints;

    public function __construct()
    {
        $user= new UserPoint();
        $this->userPoints = $user;
    }

    /**
     * add points in
     */
    public function create($data){
        return $this->userPoints->create($data);
    }
    /**
     * get points of login user
     */
    public function points(){
       return $this->userPoints->where('user_id', Auth::user()->id)->sum('point');
    }
}