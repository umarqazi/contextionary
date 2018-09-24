<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 */


/**
 * Created by PhpStorm.
 * User: haris
 * Date: 14/09/18
 * Time: 13:19
 */

namespace App\Services;


use App\Repositories\FeedbackRepo;

class FeedbackService extends BaseService implements IService
{
    /**
     * @var FeedbackRepo
     */
    protected $feedback_repo;

    /**
     * FeedbackService constructor.
     */
    public function __construct()
    {
        $feedback_repo = new FeedbackRepo();
        $this->feedback_repo = $feedback_repo;
    }

    /**
     * @return mixed
     */
    public function getListing(){
        return $this->feedback_repo->getListing();
    }

    /**
     * @return mixed
     */
    public function getListingForAuthUser($user){
        return $this->feedback_repo->getListingForAuthUser($user);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function create($request){
        return $this->feedback_repo->create($request);
    }

    /**
     * @return mixed
     */
    public function count(){
        return $this->feedback_repo->count();
    }

    /**
     * @param $id
     */
    public function read($id){
        return $this->feedback_repo->read($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function status($id){
        return $this->feedback_repo->status($id);
    }

}