<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:20
 */

namespace App\Repositories;


use App\FunFact;

class FunFactsRepo
{
    /**
     * @var FunFact
     */
    protected $fun_facts;

    /**
     * FunFactsRepo constructor.
     */
    public function __construct()
    {
        $fun_facts = new FunFact();
        $this->fun_facts = $fun_facts;
    }

    /**
     * @return mixed
     */
    public function getListing(){
        return $this->fun_facts->listing();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        return $this->fun_facts->get($id);
    }
}