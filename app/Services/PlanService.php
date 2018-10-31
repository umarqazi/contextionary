<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;


use App\Repositories\PlanRepo;

class PlanService extends BaseService implements IService
{
    /**
     * @var PlanRepo
     */
    protected $plan_repo;

    /**
     * PlanService constructor.
     */
    public function __construct()
    {
        $plan_repo          =   new PlanRepo();
        $this->plan_repo    =   $plan_repo;
    }

    /**
    * @param $id
    * @return mixed
    */
    public function get($id){
        return $this->plan_repo->get($id);
    }
}