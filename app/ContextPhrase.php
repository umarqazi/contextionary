<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextPhrase extends Model
{
    protected $table='context_phrase';
    protected $connection = 'pgsql';


    public function contexts(){
        return $this->belongsTo('App\Context', 'context_id');
    }
    public function phrases(){
        return $this->belongsTo('App\Phrase', 'phrase_id');
    }
}