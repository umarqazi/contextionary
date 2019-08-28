<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCurrentContext extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_current_context';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'current_context_id', 'last_phrase_id'];
}
