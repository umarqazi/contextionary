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

class TransactionRepo
{
    public function __construct()
    {
    }

    public function create($data){
        return Transaction::create($data);
    }
}