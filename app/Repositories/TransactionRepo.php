<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/10/18
 * Time: 6:03 PM
 */

namespace App\Repositories;

use App\Transaction;
use App\TransactionDetail;

class TransactionRepo extends BaseRepo implements IRepo
{

    protected $transaction;

    public function __construct()
    {
        $this->transaction=new Transaction();
    }

    public function create($data){
        return $this->transaction->create($data);
    }

    public function getRecord($data){
        return $this->transaction->where($data)->get();

    }
}