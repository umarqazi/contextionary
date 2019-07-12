<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class ContextTopic extends Model
{
    protected $table='context_topics';
    protected $connection = 'pgsql';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'mystery', 'image'];

    /**
     * @return mixed
     */
}
