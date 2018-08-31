<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'package_id', 'user_id', 'expiry_date', 'purchase_type', 'coin_id'
    ];

    public function users(){
        return $this->belongsTo('App\User');
    }
}
