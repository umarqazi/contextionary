<?php

namespace App\Http\Controllers\Api;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppVersionController extends Controller
{
    /**
     *
    @SWG\Post(
     *     path="/app_version",
     *     description="App Version",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    public function app_version(){

        $version = Setting::where('keys', 'app_version')->get();

        foreach ($version as $item) {

            $batch = ['app_version' => $item->values,];
        }
        if($batch){

            return json('Current app version', 200, $batch);
        }else{

            return json('App version not found!', 400);
        }
    }
}
