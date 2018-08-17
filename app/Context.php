<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    protected $table='context';
    protected $connection = 'pgsql';
}
