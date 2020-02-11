<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoffeeBreak extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table = 'coffee_break';

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    protected $fillable = ['quote', 'author'];
}
