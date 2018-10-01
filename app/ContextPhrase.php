<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextPhrase extends Model
{
    /**
     * @var string
     */
    protected $table='context_phrase';

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * @return mixed
     */
    public function getRand(){
        return self::inRandomOrder()->get()->first();
    }
}