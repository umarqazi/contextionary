<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;


use App\Repositories\SpotTheIntruderRepo;

class SpotTheIntruderService extends BaseService implements IService
{

    /**
     * @var SpotTheIntruderRepo
     */
    private $spot_the_intruder_repo;

    /**
     * SpotTheIntruderService constructor.
     */
    public function __construct()
    {
        $spot_the_intruder_repo = new SpotTheIntruderRepo();
        $this->spot_the_intruder_repo = $spot_the_intruder_repo;
    }

    /**
     * @return mixed
     */
    public function getRandom($exclude)
    {
        return $this->spot_the_intruder_repo->getRandom($exclude);
    }

    public function find($id){
        return $this->spot_the_intruder_repo->find($id);
    }

}