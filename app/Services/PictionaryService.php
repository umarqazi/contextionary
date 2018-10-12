<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;


use App\Repositories\PictionaryRepo;

class PictionaryService extends BaseService implements IService
{
    /**
     * @var PictionaryRepo
     */
    private $pictionary_repo;

    /**
     * PictionaryService constructor.
     */
    public function __construct()
    {
        $pictionary_repo = new PictionaryRepo();
        $this->pictionary_repo = $pictionary_repo;
    }

    /**
     * @param $exclude
     * @return mixed
     */
    public function getRandom($exclude)
    {
        return $this->pictionary_repo->getRandom($exclude);
    }

    /**
     * @param $ques
     * @param $answered
     * @return mixed
     */
    public function getQuestion($ques,$answered)
    {
        if($ques == $answered){
            return $this->pictionary_repo->getRandom($ques);
        }else{
            return $this->pictionary_repo->find(end($ques));
        }
    }

    public function find($id){
        return $this->pictionary_repo->find($id);
    }

}