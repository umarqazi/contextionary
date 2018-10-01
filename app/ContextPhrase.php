<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextPhrase extends Model
{
    /**
     * @var string
     */
    protected $table='context_phrase';

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
    public function getRand(){
        return self::inRandomOrder()->get()->first();
    }
}
