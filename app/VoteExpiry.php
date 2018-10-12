<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteExpiry extends Model
{
    protected $fillable = [
        'context_id', 'phrase_id', 'vote_type', 'expiry_date', 'language'
    ];
    public $timestamps = false;
}
