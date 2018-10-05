<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteMeaning extends Model
{
    protected $fillable = [
        'define_meaning_id', 'vote', 'is_poor', 'user_id', 'phrase_id', 'context_id','illustrator_id', 'type', 'translation_id', 'language'
    ];
    public $timestamps = false;

    public function meaning(){
        return $this->belongsto('App\DefineMeaning', 'define_meaning_id');
    }

    public function illustrate(){
        return $this->belongsto('App\Illustrator', 'illustrator_id');
    }

    public function translate(){
        return $this->belongsto('App\Translation', 'translation_id');
    }
}
