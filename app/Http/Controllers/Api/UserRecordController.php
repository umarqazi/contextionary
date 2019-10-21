<?php

namespace App\Http\Controllers\Api;

use App\Services\UserRecordService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRecordController extends Controller
{
    public $userrecordservice;
    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
    }

    public function UserGameRecords(Request $request){

        $data = '';
        if(!empty($request->context_id)){

            $data = $this->userrecordservice->UserGameRecords(null, null, $request->context_id);
        }
        if(!empty($request->game_id) && !empty($request->topic_id)){

            $data = $this->userrecordservice->UserGameRecords($request->game_id, $request->topic_id, null);
        }
        return json('User game data shown as:', 200, $data);
    }

    public function UserAllStatistics(){

        $data = $this->userrecordservice->UserAllStatistics();
        return json('User all stats shown as:', 200, $data);
    }

    public function UserAppLoad(){

        $data = $this->userrecordservice->UserAppLoad();
        return json('User app load status shown as:', 200, $data);
    }
}
