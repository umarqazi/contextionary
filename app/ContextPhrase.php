<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextPhrase extends Model
{
    /**
     * @var string
     */
    protected $table='context_phrase';

    public $timestamps = false;

    /**
     * @var string
     */
    protected $connection = 'pgsql';


    public function contexts(){
        return $this->belongsTo('App\Context', 'context_id');
    }
    public function phrases(){
        return $this->belongsTo('App\Phrase', 'phrase_id', 'phrase_id');
    }

    /**
     * @return mixed
     */
    public function getRand($context_id){
        return self::where('context_id', $context_id)->whereHas('phrases', function ($query) {
            $query->where('red_flag', 0)->whereRaw('LENGTH(phrase_text) > 5');
        })->where('work_order', '>',0)->inRandomOrder()->with('phrases')->limit(10)->get();
    }

    /**
     * @param $context
     * @return mixed
     */
    public function getContextPhrase($context){
         return self::where('context_id', $context)->with(['phrases' => function($query) {
            $query->where('red_flag', 0);
        }])->get();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function searchContextPhrase($key){
         return self::where('context_id', $key)->with(['phrases' => function($query) {
            $query->where('red_flag', 0);
        }])->paginate(100);
    }
}
