<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 12/09/18
 * Time: 13:34
 */

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Glossary extends Model
{
    /**
     * Notifiable Trait
     */
    use Notifiable;

    /**
     * @var string
     */
    protected $table = "glossary_items";

    /**
     * @var array
     */
    protected $fillable = ['name','price','description','thumbnail','file', 'url'];

}