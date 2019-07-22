<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhraseSprint extends Model
{

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table = 'phrase_sprint';

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * @var array
     */
    protected $fillable = ['topic_id', 'phrase_id', 'solution_context_id', 'wrong_context_id', 'bucket'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topic() {
        return $this->hasMany('App\ContextTopic', 'id', 'topic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phrase() {
        return $this->hasOne('App\Phrase', 'phrase_id', 'phrase_id')->select(['phrase_id', 'phrase_text']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function solContext() {
        return $this->hasOne('App\Context', 'context_id', 'solution_context_id')->select(['context_id', 'context_name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wrongContext() {
        return $this->hasOne('App\Context', 'context_id', 'wrong_context_id')->select(['context_id', 'context_name']);
    }
}
