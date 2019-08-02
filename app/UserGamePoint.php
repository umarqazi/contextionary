<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGamePoint extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_game_points';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'game_id', 'points', 'level'];

    public function games(){
        return $this->hasMany('App\Game', 'id', 'game_id');
    }
}
