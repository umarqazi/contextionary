<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/13/18
 * Time: 3:12 PM
 */

namespace App\Repositories;

use App\ContactUs;

class ContactUsRepo
{
    protected $message;

    public function __construct()
    {
        $message = new ContactUs;
        $this->message=$message;
    }
    public function create($data){
        return ContactUs::create($data);
    }

    /**
     * @return int
     */
    public function count(){
        return $this->message->where('status','=','0')->count();
    }

    public function read($id){
        $msg = $this->message->find($id);
        $msg->status = 1;
        $msg->update();
    }

    public function status($id){
        $msg = $this->message->find($id);
        return $msg->status;
    }

}