<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'translation','context_id', 'phrase_id','position', 'coins', 'status', 'user_id', 'language'
    ];

    /**
     * relation with user
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
