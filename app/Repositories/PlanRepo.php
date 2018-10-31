<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/18/18
 * Time: 1:04 PM
 */

namespace App\Repositories;


use App\Plan;
use App\Services\BaseService;

class PlanRepo extends BaseService implements IRepo
{
    /**
     * @var Plan
     */
    protected $plan;

    /**
     * PlanRepo constructor.
     */
    public function __construct()
    {
        $plan       =   new Plan();
        $this->plan = $plan;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return $this->plan->getPlan($id);
    }
}