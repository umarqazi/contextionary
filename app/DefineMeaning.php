<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineMeaning extends Model
{
    protected $table="define_meanings";

    protected $fillable = [
        'meaning', 'old_meaning', 'context_id', 'phrase_id', 'user_id', 'phrase_type', 'status'
    ];

    public function votes(){
        return $this->hasMany('App\VoteMeaning');
    }
    /**
     * relation with user
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
