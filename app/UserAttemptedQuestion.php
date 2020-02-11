<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttemptedQuestion extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_attempted_questions';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'game_id', 'game_type', 'attempted_id'];
}
