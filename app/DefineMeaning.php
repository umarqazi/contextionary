<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineMeaning extends Model
{
    protected $fillable = [
        'meaning', 'context_id', 'phrase_id', 'user_id', 'phrase_type'
    ];
}
