<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DefineMeaning
 * @package App
 *
 * @property string meaning
 * @property string old_meaning
 * @property integer context_id
 * @property integer phrase_id
 * @property integer user_id
 * @property string phrase_type
 * @property string old_phrase_type
 * @property integer status
 */
class DefineMeaning extends Model
{
    protected $table="define_meanings";

    protected $fillable = [
        'meaning', 'old_meaning', 'context_id', 'phrase_id', 'user_id', 'phrase_type', 'old_phrase_type', 'status'
    ];

    public function votes(){
        return $this->hasMany('App\VoteMeaning');
    }
    /**
     * relation with user
     */
    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
