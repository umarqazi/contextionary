<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id', 'name', 'amount', 'currency', 'interval',
    ];

    /**
     * @param $id
     * @return mixed
     */
    public function getPlan($id){
        return self::where('id', $id)->first();
    }
}