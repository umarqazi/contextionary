<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 4:11 PM
 */

namespace App\Repositories;

use App\Context;

class ContextRepo
{
    protected $context;
    protected $defineMeaningRepo;

    /**
     * ContextRepo constructor.
     */
    public function __construct()
    {
        $context= new Context();
        $this->defineMeaningRepo=new DefineMeaningRepo();
        $this->context=$context;
    }
    /**
     * @return mixed
     */
    public function lists(){
        return $this->context->where('context_children_id', '!=','0')->get();
    }

    /**
     * @return mixed
     */
    public function listing(){
        return $this->context->orderBy('context_name', 'asc')->get();
    }

    /**
     * @return mixed
     */
    public function get(){
        return $this->context->inRandomOrder()->first();
    }

    /**
     * @return mixed
     */
    public function findById($context){
        return $this->context->findById($context);
    }


    /**
     * @return mixed
     */
    public function findAllLike($key){
        return $this->context->findAllLike($key);
    }

    /**
     * @return mixed
     * get All Records
     */
    public function getRecords(){
        return $this->context;
    }

    /**
     * @return mixed
     */
    public function getLimitedRecords(){
        return $this->lists()->take(env('CONTEXT_LENGTH'));
    }

    /**
     * @return mixed
     */
    public function getPaginatedRecord(){
        return $this->getRecords()->paginate(9);
    }
    /**
     * @return mixed
     * get all context
     */
    public function getContext(){
        return $this->getRecords()->get();
    }
    /**
     * @return mixed
     * get one Context
     */
    public function getContextName($context_id){
        return $this->getRecords()->where('context_id', $context_id)->first();
    }
}