<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'package_id', 'user_id', 'expiry_date', 'purchase_type', 'coins', 'amount', 'status', 'sub',
    ];

    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
