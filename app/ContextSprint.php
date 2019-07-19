<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextSprint extends Model
{

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table = 'context_sprint';

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * @var array
     */
    protected $fillable = ['topic_id', 'context_id', 'solution_phrase_id', 'wrong_phrase_id', 'bucket'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topic() {
        return $this->hasMany('App\ContextTopic', 'id', 'topic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function context() {
        return $this->hasOne('App\Context', 'context_id', 'context_id')->select(['context_id', 'context_name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function solPhrase() {
        return $this->hasOne('App\Phrase', 'phrase_id', 'solution_phrase_id')->select(['phrase_id', 'phrase_text']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wrongPhrase() {
        return $this->hasOne('App\Phrase', 'phrase_id', 'wrong_phrase_id')->select(['phrase_id', 'phrase_text']);
    }
}
