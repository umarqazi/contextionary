<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrossSprint extends Model
{
    protected $table = 'cross_sprint';
    protected $connection = 'pgsql';
    protected $primaryKey = 'id';
    protected $fillable = ['topic_id', 'puzzle_word', 'bucket', 'primary_phrase', 'secondary_phrase_1', 'secondary_phrase_2', 'secondary_phrase_3'];

    public function topic(){
        return $this->hasMany('App\ContextTopic', 'id', 'topic_id');
    }

    public function hint_phrase_1(){
        return $this->hasOne('App\Phrase', 'phrase_id', 'primary_phrase');
    }

    public function hint_phrase_2(){
        return $this->hasOne('App\Phrase', 'phrase_id', 'secondary_phrase_1')->select(['phrase_id', 'phrase_text']);
    }

    public function hint_phrase_3(){
        return $this->hasOne('App\Phrase', 'phrase_id', 'secondary_phrase_2')->select(['phrase_id', 'phrase_text']);
    }

    public function hint_phrase_4(){
        return $this->hasOne('App\Phrase', 'phrase_id', 'secondary_phrase_3')->select(['phrase_id', 'phrase_text']);
    }
}
