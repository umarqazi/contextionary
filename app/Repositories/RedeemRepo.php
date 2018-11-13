<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 11/5/18
 * Time: 3:11 PM
 */

namespace App\Repositories;


use App\RedeemPoint;

class RedeemRepo extends BaseRepo implements IRepo
{

    /**
     * @var RedeemPoint
     */
    protected $redeem_point;

    /**
     * RedeemRepo constructor.
     */
    public function __construct()
    {
        $this->redeem_point = new RedeemPoint();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByID($id){
        return $this->redeem_point->findByID($id);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data){
        return $this->redeem_point->where('id', $id)->update($data);
    }
}