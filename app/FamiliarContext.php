<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FamiliarContext extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'context_id'];
}
