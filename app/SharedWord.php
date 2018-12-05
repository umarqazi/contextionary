<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedWord extends Model
{
    /**
     * @var string
     */
    protected $table='shared_word';

    public $timestamps = false;

    /**
     * @var string
     */
    protected $connection = 'pgsql';

}
