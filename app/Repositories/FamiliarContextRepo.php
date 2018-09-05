<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/17/18
 * Time: 1:09 PM
 */

namespace App\Repositories;


use App\FamiliarContext;

class FamiliarContextRepo
{
    protected $familiarContext;
    public function __construct()
    {
        $context= new FamiliarContext();
        $this->familiarContext=$context;
    }
    public function create($data){
        return $this->familiarContext->insert($data);
    }
}