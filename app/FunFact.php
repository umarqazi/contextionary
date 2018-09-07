<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class FunFact extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image', 'thumbnail', 'title', 'author', 'description',
    ];

    /**
     * @return mixed
     */
    public function listing(){
        return self::paginate();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return self::find($id);
    }
}