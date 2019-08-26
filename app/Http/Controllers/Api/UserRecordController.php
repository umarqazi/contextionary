<?php

namespace App\Http\Controllers\Api;

use App\Services\UserRecordService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRecordController extends Controller
{
    public $userrecordservice;
    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
    }

    public function UserGameRecords(){

        $data = $this->userrecordservice->ShowAllData();
        return json('User game data shown as:', 200, $data);
    }
}
