<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TextHistory extends Model
{
    protected $fillable = [
        'text', 'user_id', 'result', 'date'
    ];
}
