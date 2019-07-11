<?php

namespace App\Http\Controllers\Api;

use App\ContextTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     *
    @SWG\Post(
     *     path="/topic_list",
     *     description="Context Topics List",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    function topic_list(){

        $topics = ContextTopic::all();
        if($topics){

            return response()->json(['Data' => $topics]);
        }
    }
}
