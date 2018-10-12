<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BiddingExpiry extends Model
{
    /**
     * @var string
     */
    public $table='bidding_expiry';

    /**
     * @var bool
     */
    public $timestamps=false;

    /**
     * @return mixed
     */
    public function countPharseInDefinePhase(){
       return self::where('bid_type', env('MEANING'))->where('status',0)->count();
    }

    /**
     * @return mixed
     */
    public function countPharseInIllustrationPhase(){
       return self::where('bid_type', env('ILLUSTRATE'))->where('status',0)->count();
    }

    /**
     * @return mixed
     */
    public function countPharseInTranslationPhase(){
       return self::where('bid_type', env('TRANSLATE'))->where('status',0)->count();
    }
}
