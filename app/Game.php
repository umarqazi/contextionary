<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'games';
    protected $connection = 'pgsql';
    protected $fillable = ['name'];
}
