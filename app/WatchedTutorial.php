<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WatchedTutorial extends Model
{
    protected $fillable = ['user_id', 'watched'];
}
