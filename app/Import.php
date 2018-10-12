<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = "imports";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['file', 'type'];
}