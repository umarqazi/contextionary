<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatedPhrase extends Model
{
    /**
     * @var string
     */
    protected $table='related_phrase';

    public $timestamps = false;

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    public function contexts(){
        return $this->belongsTo('App\Context', 'context_id');
    }
    public function phrases(){
        return $this->belongsTo('App\Phrase', 'context_phrase_id');
    }
    public function relatedPhrases(){
        return $this->belongsTo('App\Phrase', 'related_phrase_id');
    }
}
