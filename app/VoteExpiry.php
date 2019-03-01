<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VoteExpiry
 * @package App
 *
 * @property integer context_id
 * @property integer phrase_id
 * @property integer vote_type
 * @property integer expiry_date
 * @property integer language
 */
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
        return self::where('vote_type', env('MEANING'))->where('status',0)->count();
    }

    /**
     * @return mixed
     */
    public function countPharseInIllustrationPhase(){
        return self::where('vote_type', env('ILLUSTRATE'))->where('status',0)->count();
    }

    /**
     * @return mixed
     */
    public function countPharseInTranslationPhase(){
        return self::where('vote_type', env('TRANSLATE'))->where('status',0)->count();
    }
}
