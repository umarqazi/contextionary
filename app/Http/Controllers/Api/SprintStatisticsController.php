<?php

namespace App\Http\Controllers\Api;

use App\SprintStatistic;
use App\UnlockedSprintMystery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SprintStatisticsController extends Controller
{
    public function SprintStatistic(Request $request){

        $msg = '';
        $code = '';
        $update_statistics = SprintStatistic::where(['user_id' => auth()->id(), 'game_id' => $request->game_id, 'topic_id' => $request->topic_id])->first();
        if($update_statistics){
            if($request->correct_answers > $update_statistics->no_of_correct_answers){

                $update_statistics->no_of_correct_answers = $request->correct_answers;
            }

            if($request->points > $update_statistics->points){

                $update_statistics->points = $request->points;
            }

            if($request->best_time < $update_statistics->best_time){

                $update_statistics->best_time = $request->best_time;
            }

            $update_statistics->has_cup = $request->has_cup;
            $update_statistics->completed = $request->completed;
            $updated = $update_statistics->save();
            if($updated){
                $msg = 'Sprint statistic has updated.';
                $code = '200';
            }else{
                $msg = 'Something wrong here!';
                $code = '400';
            }

        } else {

            $sprint_statistics = SprintStatistic::create([
                'user_id' => auth()->id(),
                'game_id' => $request->game_id,
                'topic_id' => $request->topic_id,
                'no_of_correct_answers' => $request->correct_answers,
                'points' => $request->points,
                'best_time' => $request->best_time,
                'completed' => $request->completed,
                'has_cup' => $request->has_cup
            ]);
            if($sprint_statistics){
                $msg = 'Sprint statistic has inserted.';
                $code = '200';
            }else{
                $msg = 'Something wrong here!';
                $code = '400';
            }
        }

        $unlocked_sprint = SprintStatistic::where(['user_id' => auth()->id(), 'game_id' => $request->game_id, 'has_cup' => 1])->first();

        if($unlocked_sprint) {

            $unlocked_sprint_id = $request->game_id+1;

            $check_unlocked = UnlockedSprintMystery::where(['user_id' => auth()->id(), 'unlocked_sprint' => $unlocked_sprint_id])->get();

            if($check_unlocked->isEmpty()){

                $unlocked_sprint = UnlockedSprintMystery::create([
                    'user_id' => auth()->id(),
                    'unlocked_sprint' => $unlocked_sprint_id
                ]);
            }
        }
        return json($msg, $code);
    }

    public function SprintMysteryTopic(Request $request){

        $msg = '';
        $code = '';

        //Unlocked mystery topic
        $update_mystery_topic = UnlockedSprintMystery::where(['user_id' => auth()-> id(), 'unlocked_mystery_topic' => $request->unlocked_mystery_topic])->get();
        $check_unlocked = UnlockedSprintMystery::where(['user_id' => auth()->id(), 'unlocked_sprint' => $request->unlocked_sprint])->get();

        if($update_mystery_topic->isEmpty() || $check_unlocked->isEmpty()) {

            $unlocked_mystery_topic = UnlockedSprintMystery::create([
                'user_id' => auth()->id(),
                'unlocked_mystery_topic' => $request->unlocked_mystery_topic,
                'unlocked_sprint' => $request->unlocked_sprint
            ]);
            if ($unlocked_mystery_topic) {
                $msg = 'Unlocked mystery topic has been added.';
                $code = '200';
            } else {
                $msg = 'Something wrong here!';
                $code = '400';
            }
        } else{
            $msg = 'Record already exist!';
            $code = '200';
        }
        return json($msg, $code);
    }
}
