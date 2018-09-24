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

class Setting extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['keys', 'values', 'pic3', 'pic4', 'option1', 'option2', 'option3', 'option4', 'question','answer'];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListing(){
        return self::all();
    }

}