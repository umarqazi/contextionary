<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    /**
     * @var string
     */
    protected $table='phrase';

    /**
     * @var string
     */
    protected $connection = 'pgsql';
    protected $primaryKey = 'phrase_id';


    public function contexts(){
        return $this->belongsToMany('App\Context', 'context_phrase', 'phrase_id', 'context_id')->withPivot('work_order');
    }
  
    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return self::where('phrase_id', $id)->get();
    }

    /**
     * @param $length
     * @return mixed
     */
    public function getLengthed($length){
        return self::where('phrase_length', $length)->inRandomOrder()->first();
    }
}
