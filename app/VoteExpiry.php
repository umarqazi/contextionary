<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteExpiry extends Model
{
    protected $fillable = [
        'context_id', 'phrase_id', 'vote_type', 'expiry_date', 'language'
    ];
    public $timestamps = false;

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
