<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    protected $table='context';
    protected $connection = 'pgsql';
    protected $primaryKey = 'context_id';

    /**
     * @return mixed
     */
    public function phrases(){
        return $this->belongsToMany('App\Phrase', 'context_phrase' , 'context_id', 'phrase_id')->withPivot('work_order');
    }

    /**
     * @return mixed
     */
    public function getPhrases()
    {
        return $this->phrases()->wherePivot('work_order','!=', NULL);
    }
}
