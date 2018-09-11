<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/10/18
 * Time: 12:00 PM
 */

namespace App\Repositories;


use App\BiddingExpiry;
use Carbon\Carbon;

class BiddingExpiryRepo
{
    protected $bidding;

    public function __construct()
    {
        $bidding=new BiddingExpiry();
        $this->bidding=$bidding;
    }

    /**
     * update expiry date
     */
    public function updateRecord($context_id, $phrase_id){
        return $this->bidding->where(['context_id'=>$context_id, 'phrase_id'=>$phrase_id])->update(['expiry_date'=>Carbon::yesterday()]);
    }
}