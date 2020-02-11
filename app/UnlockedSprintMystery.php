<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnlockedSprintMystery extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'unlocked_sprint_mystery';
    protected $fillable = ['user_id', 'unlocked_sprint', 'unlocked_mystery_topic'];
}
