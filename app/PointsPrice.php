<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PointsPrice extends Model
{
    use Notifiable;

    protected $table = 'points_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'min_points','max_points','price'
    ];

}