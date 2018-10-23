<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $fillable = [
        'card_id', 'last4', 'exp_month', 'exp_year', 'brand','user_id'
    ];

    /**
     * relation with users
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
