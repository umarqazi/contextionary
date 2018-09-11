<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteMeaning extends Model
{
    protected $fillable = [
        'grammer', 'define_meaning_id', 'spelling', 'audience', 'part_of_speech', 'vote', 'is_poor', 'user_id', 'phrase_id', 'context_id'
    ];
    public $timestamps = false;

    public function meaning(){
        return $this->belongsto('App\DefineMeaning');
    }
}
