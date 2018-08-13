<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'package_id', 'user_id', 'expiry_date'
    ];

    public function users(){
        return $this->belongsTo('App\User');
    }
}
