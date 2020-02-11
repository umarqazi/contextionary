<?php

namespace App\Http\Controllers\Api;

use App\ContextMarathonStatistic;
use App\Services\MarathonStatisticService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContextMarathonStatisticController extends Controller
{
    public $marathonstatisticservice;
    public function __construct()
    {
        $this->marathonstatisticservice = new MarathonStatisticService();
    }

    public function MarathonStatistic(Request $request){

        $data = $this->marathonstatisticservice->AddUserAllMarathonStatistics($request->all());
        return json('Marathon Stats shown as:', 200, $data);
    }

    public function LastPlayedMarathonRecord(Request $request){

        $data = $this->marathonstatisticservice->LastPlayedMarathonRecord($request->all());
        return json('Last Played Marathon Record shown as:', 200, $data);
    }
}
