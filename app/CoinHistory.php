<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinHistory extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'coins_history';
    protected $fillable = ['user_id', 'game_session', 'game_id', 'topic_id', 'context_id', 'mode', 'type', 'coins'];
}
