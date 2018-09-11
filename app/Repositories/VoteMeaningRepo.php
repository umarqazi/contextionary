<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 4:29 PM
 */

namespace App\Repositories;

use App\VoteMeaning;

class VoteMeaningRepo
{
    protected $voteMeaning;

    public function __construct()
    {
        $vote=new VoteMeaning();
        $this->voteMeaning=$vote;
    }

    public function create($data){
        return $this->voteMeaning->create($data);
    }

}