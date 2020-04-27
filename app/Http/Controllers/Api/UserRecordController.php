<?php

namespace App\Http\Controllers\Api;

use App\Services\MarathonStatisticService;
use App\Services\TutorialListService;
use App\Services\UserRecordService;
use App\Services\WatchedTutorialService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class UserRecordController extends Controller
{
    public $userrecordservice;
    public $marathonstatisticservice;
    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
        $this->marathonstatisticservice = new MarathonStatisticService();
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

    public function UserMarathonStatistics(Request $request){

        $data = $this->userrecordservice->UserMarathonStatistics($request->all());
        if($data){

            return json('Marathon Statistics has been saved successfully', 200);
        }else{

            return json('Something wrong here!', 400);
        }
    }

    public function UserGameLoad(){

        $data = $this->userrecordservice->UserGameLoad();
        if($data){

            return json('User game load status shown as', 200, $data);
        }else{

            return json('Something wrong here!', 400);
        }
    }

    public function UserSprintStatistics(Request $request){

        $data = $this->userrecordservice->UserSprintStatistics($request->all());
        if($data){

            return json('Sprint Statistics has been saved successfully', 200);
        }else{

            return json('Something wrong here!', 400);
        }
    }

    /**
     * Method: watchedTutorials
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function watchedTutorials(Request $request)
    {
        $data = App::make(WatchedTutorialService::class)->watchedTutorials($request->all(), auth()->id());
        if($data){

            return json('Watched tutorials saved successfully!', 200);
        }else{

            return json('Something wrong here!', 400);
        }
    }

    /**
     * Method: tutorialsList
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tutorialsList()
    {
        $data = App::make(TutorialListService::class)->tutorialsList(auth()->id());
        if($data){

            return json('Tutorials list shown as:!', 200, $data);
        }else{

            return json('Something wrong here!', 400);
        }
    }
}
