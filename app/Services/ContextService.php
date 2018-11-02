<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;


use App\Repositories\ContextRepo;

class ContextService extends BaseService implements IService
{
    /**
     * @var ContextRepo
     */
    protected $context_repo;

    /**
     * ContextService constructor.
     */
    public function __construct()
    {
        $context_repo = new ContextRepo();
        $this->context_repo = $context_repo;
    }

    /**
     * @return mixed
     */
    public function listing(){
        return $this->context_repo->listing();
    }

    /**
     * @param $context
     * @return mixed
     */
    public function findById($context){
        return $this->context_repo->findById($context);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function findAllLike($key){
        return $this->context_repo->findAllLike($key);
    }
}