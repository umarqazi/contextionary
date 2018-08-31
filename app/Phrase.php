<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    protected $table='phrase';
    protected $connection = 'pgsql';
}
