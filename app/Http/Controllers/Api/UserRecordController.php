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

    public function UserGameRecords(){

        $data = $this->userrecordservice->ShowAllData();
        return json('User game data shown as:', 200, $data);
    }

    public function UpdateUserInfo(Request $request){

        $update_info = User::where('id', auth()->id())->first();
        if($update_info){

            if(isset($request->game_coins)){

                $update_info->game_coins = $request->game_coins;
            }
            if(isset($request->aladdin_lamp)){

                $update_info->aladdin_lamp = $request->aladdin_lamp;
            }
            if(isset($request->butterfly_effect)){

                $update_info->butterfly_effect = $request->butterfly_effect;
            }
            $updated = $update_info->save();
            if($updated){
                return json('User info updated', 200);
            }else{
                return json('Something went wrong!', 400);
            }
        }
    }
}
