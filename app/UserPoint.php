<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $fillable = [
        'point', 'context_id', 'phrase_id', 'user_id', 'position', 'type'
    ];
}
