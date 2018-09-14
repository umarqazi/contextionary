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
    /*
     * get current bidding
     */
    public function fetchBidding($type){
        return $records=$this->bidding->where(['status'=> 0, 'bid_type'=>$type])->get();
    }
    /**
     * update bidding status
     */
    public function updateBiddingStatus($id){
        return $this->bidding->where('id', $id)->update(['status'=>'1']);
    }
    /**
     * @param $context_id
     * @param $phrase_id
     * @return bool
     */
    public function addBidExpiry($data, $type){
        $date=Carbon::now()->addMonths(1);
        return $this->bidding->insert(['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'],'bid_type'=>$data['type'], 'expiry_date'=>$date]);
    }
}