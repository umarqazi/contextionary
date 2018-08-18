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
    public function __construct(ContactUs $message)
    {
        $this->message=$message;
    }
    public function create($data){
        return ContactUs::create($data);
    }
}