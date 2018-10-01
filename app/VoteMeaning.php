<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteMeaning extends Model
{
    protected $fillable = [
        'grammer', 'define_meaning_id', 'spelling', 'audience', 'part_of_speech', 'vote', 'is_poor', 'user_id', 'phrase_id', 'context_id','illustrator_id', 'type'
    ];
    public $timestamps = false;

    public function meaning(){
        return $this->belongsto('App\DefineMeaning', 'define_meaning_id');
    }

    public function illustrate(){
        return $this->belongsto('App\Illustrator', 'illustrator_id');
    }
}
