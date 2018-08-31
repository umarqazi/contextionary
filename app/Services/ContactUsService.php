<?php
/**
 * Author: Muhammad Adeel
 * Date: 8/13/18
 * Time: 3:10 PM
 */

namespace App\Services;

use App\Repositories\ContactUsRepo;

class ContactUsService extends BaseService implements IService
{
    /**
     * @var ContactUsRepo
     */
    protected $contactUs;

    /**
     * ContactUsService constructor.
     */
    public function __construct()
    {
        $messageRepo = new ContactUsRepo;
        $this->contactUs=$messageRepo;
    }

    /**
     * @param $data
     * @return bool
     */
    public function saveMessage($data){
        $data=['first_name'=>$data->first_name, 'last_name'=>$data->last_name, 'email'=>$data->email, 'message'=>$data->message, 'status'=>'0'];
        $createRecord=$this->contactUs->create($data);
        return true;
    }

    /**
     * @return mixed
     */
    public function count(){
        return $this->contactUs->count();
    }

    /**
     * @param $id
     */
    public function read($id){
        return $this->contactUs->read($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function status($id){
        return $this->contactUs->status($id);
    }

}