<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
  protected $fillable = [
      'transaction_id', 'package_id', 'user_id'
  ];
}
