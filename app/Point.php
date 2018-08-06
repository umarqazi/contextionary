<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','slug', 'sub_points',
    ];

    public function packages() {
        return $this->belongsToMany('App\Package', 'packages_points',  'point_id', 'package_id')->withTimestamps();

    }
}