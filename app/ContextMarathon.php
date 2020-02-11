<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextMarathon extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'context_marathon';
    protected $primaryKey = 'id';
    protected $fillable = ['context_id', 'phrase_id', 'bucket', 'hint_1', 'hint_2', 'hint_3', 'hint_4', 'hint_5', 'hint_6', 'manual_shuffle'];
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function context(){

        return $this->hasMany('App\Context', 'context_id', 'context_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phrase(){

        return $this->hasOne('App\Phrase', 'phrase_id', 'phrase_id')->select(['phrase_id', 'phrase_text']);
    }
}
