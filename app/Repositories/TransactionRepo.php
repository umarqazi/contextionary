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

    /**
     * TransactionRepo constructor.
     */
    public function __construct()
    {
        $this->transaction=new Transaction();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){
        return $this->transaction->create($data);
    }

    /**
     * @param $check
     * @param $data
     * @return mixed
     */
    public function update($check,$data){
        return $this->transaction->where($check)->update($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getRecord($data){
        return $this->transaction->where($data)->get();

    }

    /**
     * @return mixed
     */
    public function getLasttransaction(){
        return $this->transaction->select('id')->latest()->first();
    }
}