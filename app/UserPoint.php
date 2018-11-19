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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function context()
    {
        return $this->hasOne('App\Context', 'context_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phrase()
    {
        return $this->hasOne('App\Phrase', 'phrase_id');
    }
}
