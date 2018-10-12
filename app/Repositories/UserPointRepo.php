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

    /**
     * UserPointRepo constructor.
     */
    public function __construct()
    {
        $user= new UserPoint();
        $this->userPoints = $user;
    }

    /**
     * @param $data
     * @return mixed
     * add points in
     */
    public function create($data){
        return $this->userPoints->create($data);
    }

    /**
     * @return mixed
     * get points of login user
     */
    public function points(){
       return $this->userPoints->where('user_id', Auth::user()->id)->selectRaw('sum(point) as sum, type')->groupBy('type')->get();
    }

    /**
     * @return mixed
     * get user pole positions
     */
    public function postions(){
        return $this->userPoints->where(['user_id'=>Auth::user()->id, 'position'=>1])->selectRaw('count(position) as total, type')->groupBy('type')->get();
    }

    /**
     * @return mixed
     * get user runnerUp positions
     */
    public function runnerUp(){
        return $this->userPoints->where('user_id',Auth::user()->id)->where('position', '!=', 1)->selectRaw('count(position) as total, type')->groupBy('type')->get();
    }
    /**
     * @return mixed
     * get points of other user
     */
    public function otherContributors(){
        return $this->userPoints->where('user_id','!=', Auth::user()->id)->selectRaw('sum(point) as sum, type')->groupBy('type')->get();
    }
}