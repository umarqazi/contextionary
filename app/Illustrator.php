<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Illustrator extends Model
{
    protected $fillable = [
        'illustrator','context_id', 'phrase_id','position', 'coins', 'status', 'user_id'
    ];

    /**
     * relation with user
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
