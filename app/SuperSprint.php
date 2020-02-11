<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuperSprint extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table = 'super_sprint';

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * @var array
     */
    protected $fillable = ['topic_id', 'hint', 'incorrect_phrase_id', 'phrase_id', 'bucket'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topic()
    {
        return $this->hasMany('App\ContextTopic', 'id', 'topic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phrase()
    {
        return $this->hasOne('App\Phrase', 'phrase_id', 'phrase_id')->select(['phrase_id', 'phrase_text']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wrong_phrase_id()
    {
        return $this->hasOne('App\Phrase', 'phrase_id', 'incorrect_phrase_id')->select(['phrase_id', 'phrase_text']);
    }
}
