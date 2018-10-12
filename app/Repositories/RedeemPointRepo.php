<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 10/9/18
 * Time: 11:48 AM
 */

namespace App\Repositories;


use App\RedeemPoint;
use Auth;

class RedeemPointRepo
{

    protected $redeemPoints;

    /**
     * RedeemPointRepo constructor.
     */
    public function __construct()
    {
        $this->redeemPoints=new RedeemPoint();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){
        return $this->redeemPoints->create($data);
    }
    /**
     * @return mixed
     * get redeem points of login user
     */
    public function points(){
        return $this->redeemPoints->where('user_id', Auth::user()->id)->selectRaw('sum(points) as sum, type')->groupBy('type')->get();
    }
    /**
     * @return mixed
     * get redeem points of other users
     */
    public function otherContributors(){
        return $this->redeemPoints->where('user_id','!=', Auth::user()->id)->selectRaw('sum(earning) as sum, type')->groupBy('type')->get();
    }
}