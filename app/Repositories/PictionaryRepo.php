<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Repositories;
use App\Pictionary;

class PictionaryRepo extends BaseRepo implements IRepo
{

    /**
     * @var Pictionary
     */
    private $pictionary;

    public function __construct()
    {
        $pictionary = new Pictionary();
        $this->pictionary = $pictionary;
    }

    /**
     * @return mixed
     */
    public function getRandom($exclude)
    {
        return $this->pictionary->getRandom($exclude);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->pictionary->find($id);
    }
}