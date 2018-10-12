<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/10/18
 * Time: 12:00 PM
 */

namespace App\Repositories;


use App\BiddingExpiry;
use App\Http\Controllers\SettingController;
use Carbon\Carbon;
use Auth;

class BiddingExpiryRepo extends BaseRepo implements IRepo
{
    protected $bidding;
    protected $setting;

    public function __construct()
    {
        $bidding        =   new BiddingExpiry();
        $this->bidding  =   $bidding;
        $this->setting  =   new SettingController();

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
    public function updateBiddingStatus($id, $data){
        return $this->bidding->where('id', $id)->update($data);
    }
    /**
     * @param $context_id
     * @param $phrase_id
     * @return bool
     */
    public function addBidExpiry($data, $type){
        $setting=$this->setting->getKeyValue(env('BIDS_EXPIRY'));
        if($setting){
            $this->total_context=$setting->values;
        }
        $date=Carbon::now()->addMinutes($this->total_context);
        $insert_record=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'],'bid_type'=>$data['type'], 'expiry_date'=>$date];
        if($data['type']==env('TRANSLATE')):
            $insert_record['language']=Auth::user()->profile->language_proficiency;
        endif;
        return $this->bidding->insert($insert_record);
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @param $type
     */
    public function checkPhraseExpiry($context_id, $phrase_id, $type){
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id,'status'=> 0, 'bid_type'=>$type];
        if($type==env('TRANSLATE')):
            $data['language']=Auth::user()->profile->language_proficiency;
        endif;
        return $records=$this->bidding->where($data)->first();
    }
}