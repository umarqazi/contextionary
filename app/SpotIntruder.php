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

class SpotIntruder extends Model
{
    /**
     * @var string
     */
    protected $table="spot_intruder";

    /**
     * @var array
     */
    protected $fillable = ['option1', 'option2', 'option3', 'option4', 'question','answer'];

    /**
     * @param $exclude
     * @return mixed
     */
    public function getRandom($exclude){
        return self::inRandomOrder()->whereNotIn('id', $exclude)->get()->first();
    }
}