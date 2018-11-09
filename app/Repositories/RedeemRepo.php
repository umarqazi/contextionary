<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 11/5/18
 * Time: 3:11 PM
 */

namespace App\Services;


use App\Repositories\TextHistoryRepo;

class RedeemService extends BaseService implements IService
{

    protected $redeem_repo;

    public function __construct()
    {
        $this->redeem_repo = new TextHistoryRepo();
    }

    public function findByID($id){
        return $this->redeem_repo->findByID($id);
    }
}