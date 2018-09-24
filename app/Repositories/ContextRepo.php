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
    public function __construct()
    {
        $context= new Context();
        $this->context=$context;
    }
    public function list(){
        return $this->context->where('context_children_id', '!=','0')->get();
    }

    /**
     * @return mixed
     * get All Records
     */
    public function getRecords(){
        return $this->context;
    }
    public function getLimitedRecords(){
        return $this->list()->take(env('CONTEXT_LENGTH'));
    }
    public function getPaginatedRecord(){
        return $this->getRecords()->paginate(9);
    }
    /**
     * get all context
     */
    public function getContext(){
        return $this->getRecords()->get();
    }
}