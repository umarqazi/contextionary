<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 10/1/18
 * Time: 2:36 PM
 */

namespace App\Repositories;


use App\Phrase;

class PhraseRepo
{
    protected $phrase;

    /**
     * ContextRepo constructor.
     */
    public function __construct()
    {
        $this->phrase= new Phrase();
    }
    
    /**
     * @param $phrase_id
     * @return mixed
     */
    public function getPhraseName($phrase_id){
        return $this->phrase->where('phrase_id', $phrase_id)->first();
    }
}