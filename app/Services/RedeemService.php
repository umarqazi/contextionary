<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 11/5/18
 * Time: 3:11 PM
 */

namespace App\Services;


use App\Repositories\RedeemRepo;

class RedeemService extends BaseService implements IService
{

    /**
     * @var RedeemRepo
     */
    protected $redeem_repo;

    /**
     * RedeemService constructor.
     */
    public function __construct()
    {
        $this->redeem_repo = new RedeemRepo();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByID($id){
        return $this->redeem_repo->findByID($id);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data){
        return $this->redeem_repo->update($id, $data);
    }
}