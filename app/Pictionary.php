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

class Pictionary extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['pic1', 'pic2', 'pic3', 'pic4', 'option1', 'option2', 'option3', 'option4', 'question','answer'];

    /**
     * @return mixed
     */
    public function getRandom($exclude){
        return self::inRandomOrder()->whereNotIn('id', $exclude)->get()->first();
    }
}