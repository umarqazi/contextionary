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

class PictionaryGame extends Model
{
    /**
     * @var string
     */
    protected $table = 'pictionary_game';

    /**
     * @var array
     */
    protected $fillable = ['score', 'question_count', 'questions', 'is_complete'];

    /**
     * @param $user_id
     * @return mixed
     */
    public function incompleteUserGame($user_id){
        return self::where('is_complete', 0)->where('user_id', $user_id)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->find($id);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getHighScore($user_id){
        return self::where('user_id', $user_id )->max('score');
    }
}