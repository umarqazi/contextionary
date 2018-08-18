<?php
/**
 * Author: Muhammad Adeel
 * Date: 8/13/18
 * Time: 3:10 PM
 */

namespace App\Services;

use App\Repositories\ContactUsRepo;

class ContactUsService
{
    protected $contactUs;
    public function __construct(ContactUsRepo $messageRepo)
    {
        $this->contactUs=$messageRepo;
    }
    public function saveMessage($data){
        $data=['first_name'=>$data->first_name, 'last_name'=>$data->last_name, 'email'=>$data->email, 'message'=>$data->message, 'status'=>'0'];
        $createRecord=$this->contactUs->create($data);
        return true;
    }
}