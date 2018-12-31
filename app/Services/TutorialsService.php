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
use App\Repositories\TutorialsRepo;

class TutorialsService extends BaseService implements IService
{
    /**
     * @var TutorialsRepo
     */
    protected $tutorials_repo;

    /**
     * TutorialsService constructor.
     */
    public function __construct()
    {
        $tutorials_repo = new TutorialsRepo();
        $this->tutorials_repo = $tutorials_repo;
    }

    /**
     * @return mixed
     */
    public function firstUser(){
        return $this->tutorials_repo->firstUser();
    }

    /**
     * @return mixed
     */
    public function firstContributor(){
        return $this->tutorials_repo->firstContributor();
    }
}