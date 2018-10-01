<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    protected $table='phrase';
    protected $connection = 'pgsql';
    protected $primaryKey = 'phrase_id';


    public function contexts(){
        return $this->belongsToMany('App\Context', 'context_phrase', 'phrase_id', 'context_id')->withPivot('work_order');
    }
}
