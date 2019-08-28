<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserUnlockedContext extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_unlocked_contexts';
    protected $fillable = ['user_id', 'unlocked_context'];
}
