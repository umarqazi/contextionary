<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $fillable = [
        'point', 'context_id', 'phrase_id', 'user_id', 'position', 'type'
    ];

    /**
     * relation with users
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
