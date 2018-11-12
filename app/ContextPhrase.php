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
        return $this->belongsTo('App\Phrase', 'phrase_id');
    }

    /**
     * @return mixed
     */
    public function getRand($context_id){
        return self::where('context_id', $context_id)->whereHas('phrases', function ($query) {
            $query->where('phrase_length', '=', 1)->whereRaw('LENGTH(phrase_text) > 5');
        })->inRandomOrder()->with('phrases')->limit(10)->get();
    }

    /**
     * @return mixed
     */
    public function getContextPhrase($context){
        return self::where('context_id', $context)->with('phrases')->paginate(100);
    }
}
