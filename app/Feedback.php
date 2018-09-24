<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 31/08/18
 * Time: 16:48
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'email', 'message', 'status'];

    /**
     * @var string
     */
    protected $table = 'feedback';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListing(){
        return self::all();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createFeedback($request){
        return self::create(array(
            'user_id'   => $request->user_id,
            'email'     => $request->email,
            'message'   => $request->message,
            'status'    => '0'
        ));
    }
}