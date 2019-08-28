<?php

namespace App\Http\Controllers\Api;

use App\ContextMarathonStatistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContextMarathonStatisticController extends Controller
{
    public function MarathonStatus(Request $request){

        $marathon_stats = ContextMarathonStatistic::where(['user_id' => auth()->id(), 'context_id' => $request->context_id])->first();
        if($marathon_stats){

            if($marathon_stats->status == 0){

                $marathon_stats->points = $request->points;
                $marathon_stats->bucket = $request->bucket;
                $marathon_stats->status = $request->status;
                $updated = $marathon_stats->save();
                if($updated){
                    return json('User status has been updated', 200);
                }else{
                    return json('Something went wrong!', 400);
                }

            } elseif($marathon_stats->status == 1){

                $marathon_stats->bucket = $request->bucket;
                $updated = $marathon_stats->save();
                if($updated){
                    return json('User status has been updated', 200);
                }else{
                    return json('Something went wrong!', 400);
                }
            }
        } else{

            $validate = Validator::make($request->all(), [
                'context_id' => 'required|integer',
                'points' => 'required|integer',
                'bucket' => 'required|integer',
                'status' => 'required|integer'
            ]);
            if($validate->fails()){

                return json($validate->messages()->first(), 400);
            } else {

                $marathon_statistics = ContextMarathonStatistic::create([

                    'user_id' => auth()->id(),
                    'context_id' => $request->context_id,
                    'points' => $request->points,
                    'bucket' => $request->bucket,
                    'status' => $request->status
                ]);
                if($marathon_statistics){

                    return json('Status has been saved successfully', 200);
                }else{

                    return json('Something wrong here!', 400);
                }
            }
        }
    }
}
