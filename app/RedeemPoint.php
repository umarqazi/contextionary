<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedeemPoint extends Model
{
    protected $fillable = [
        'points', 'earning', 'type','status', 'user_id'
    ];


    /**
     * relation with users
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
