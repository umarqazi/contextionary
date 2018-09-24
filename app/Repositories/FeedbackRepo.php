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
 * Time: 13:12
 */

namespace App\Repositories;


use App\Feedback;

class FeedbackRepo extends BaseRepo implements IRepo
{
    /**
     * @var Feedback
     */
    protected $feedback;

    /**
     * FeedbackRepo constructor.
     */
    public function __construct()
    {
        $feedback = new Feedback();
        $this->feedback = $feedback;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListing()
    {
        return $this->feedback->getListing();
    }

    /**
     * @return mixed
     */
    public function getListingForAuthUser($user){
        return $user->feedback()->get();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function create($request){
        return $this->feedback->createFeedback($request);
    }

    /**
     * @return int
     */
    public function count(){
        return $this->feedback->where('status','=','0')->count();
    }

    public function read($id){
        $msg = $this->feedback->find($id);
        $msg->status = 1;
        $msg->update();
    }

    public function status($id){
        $msg = $this->feedback->find($id);
        return $msg->status;
    }
}