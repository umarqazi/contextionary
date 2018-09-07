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

}