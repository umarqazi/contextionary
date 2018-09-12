<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Repositories;
use App\SpotIntruder;

class SpotTheIntruderRepo extends BaseRepo implements IRepo
{

    /**
     * @var SpotIntruder
     */
    private $spot_intruder;

    public function __construct()
    {
        $spot_intruder = new SpotIntruder();
        $this->spot_intruder = $spot_intruder;
    }

    /**
     * @return mixed
     */
    public function getRandom($exclude)
    {
        return $this->spot_intruder->getRandom($exclude);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->spot_intruder->find($id);
    }
}