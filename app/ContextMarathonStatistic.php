<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextMarathonStatistic extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'context_marathon_statistics';
    protected $fillable = ['user_id', 'context_id', 'points', 'bucket', 'status'];
}
