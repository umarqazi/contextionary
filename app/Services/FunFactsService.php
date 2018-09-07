<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:08
 */

namespace App\Services;


use App\Repositories\FunFactsRepo;

class FunFactsService extends BaseService implements IService
{
    /**
     * @var
     */
    protected $fun_facts_repo;

    /**
     * FunFactsService constructor.
     */
    public function __construct()
    {
        $fun_facts_repo = new FunFactsRepo();
        $this->fun_facts_repo = $fun_facts_repo;
    }

    /**
     * @return mixed
     */
    public function getListing(){
        return $this->fun_facts_repo->getListing();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return $this->fun_facts_repo->get($id);
    }
}