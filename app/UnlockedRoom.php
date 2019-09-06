<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnlockedRoom extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_unlocked_rooms';
    protected $fillable = ['user_id', 'room_id', 'door_id'];
}
