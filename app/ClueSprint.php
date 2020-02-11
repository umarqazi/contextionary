<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClueSprint extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table = 'clue_sprint';

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * @var array
     */
    protected $fillable = ['topic_id', 'context_id', 'phrase_id', 'word_to_replace', 'replacement_id_1', 'replacement_id_2', 'replacement_id_3', 'bucket'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topic(){

        return $this->hasMany('App\ContextTopic', 'id', 'topic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function context(){

        return $this->hasOne('App\Context', 'context_id', 'context_id')->select(['context_id', 'context_name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phrase(){

        return $this->hasOne('App\Phrase', 'phrase_id', 'phrase_id')->select(['phrase_id', 'phrase_text']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wrong_replacement_id_1(){

        return $this->hasOne('App\Phrase', 'phrase_id', 'replacement_id_1')->select(['phrase_id', 'phrase_text']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wrong_replacement_id_2(){

        return $this->hasOne('App\Phrase', 'phrase_id', 'replacement_id_2')->select(['phrase_id', 'phrase_text']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wrong_replacement_id_3(){

        return $this->hasOne('App\Phrase', 'phrase_id', 'replacement_id_3')->select(['phrase_id', 'phrase_text']);
    }
}
