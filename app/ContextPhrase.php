<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextPhrase extends Model
{
    protected $table='context_phrase';
    protected $connection = 'pgsql';
}