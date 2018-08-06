<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Point;

class Package extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price',
    ];

    public function points() {
        return $this->belongsToMany('App\Point', 'packages_points', 'package_id', 'point_id')->withTimestamps();
    }
}