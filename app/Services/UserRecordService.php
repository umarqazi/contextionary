<?php


namespace App\Services;


use App\ContextMarathonStatistic;
use App\SprintStatistic;
use App\User;
use http\Env\Request;

class UserRecordService extends BaseService implements IService
{

 public function MarathonStatistics($context_id){

     $marathon_statistics = ContextMarathonStatistic::where(['user_id' => auth()->id(), 'context_id' => $context_id])->first();

     if($marathon_statistics){

         $data[] = [
             'context_id' => $marathon_statistics->context_id,
             'points' => $marathon_statistics->points,
             'bucket' => $marathon_statistics->bucket,
             'status' => $marathon_statistics->status,
         ];
         return $data;
     }
 }

 public function UserInfo(){

     $user_info = User::where('id', auth()->id())->first();
     $data[] = [
         'game_coins' => $user_info->game_coins,
         'aladdin_lamp' => $user_info->aladdin_lamp,
         'butterfly_effect' => $user_info->butterfly_effect,
         'stopwatch' => $user_info->stopwatch,
         'time_traveller' => $user_info->time_traveller,
     ];
     return $data;
 }

 public function SprintStatistics($game_id, $topic_id){

     $sprint_statistics = SprintStatistic::where(['user_id' => auth()->id(), 'game_id' => $game_id, 'topic_id' => $topic_id])->first();
     if($sprint_statistics) {
         $data[] = [
             'game_id' => $sprint_statistics->game_id,
             'topic_id' => $sprint_statistics->topic_id,
             'no_of_correct_answers' => $sprint_statistics->no_of_correct_answers,
             'points' => $sprint_statistics->points,
             'best_time' => $sprint_statistics->best_time,
             'completed' => $sprint_statistics->completed,
             'has_cup' => $sprint_statistics->has_cup
         ];
         return $data;
     }
 }

 public function ShowAllData($game_id=null, $topic_id=null, $context_id=null){

     $data['marathon_statistics'] = $this->MarathonStatistics($context_id);
     $data['user_info'] = $this->UserInfo();
     $data['sprint_statistics'] = $this->SprintStatistics($game_id, $topic_id);
     return $data;
 }


    public function AllStatistics(){
        $sprint_statistics = SprintStatistic::where('user_id', auth()->id())->get();
        if(!$sprint_statistics->isEmpty()) {
            foreach ($sprint_statistics as $sprint_statistic) {

                $sprint_data[] = [
                    'game_id' => $sprint_statistic->game_id,
                    'topic_id' => $sprint_statistic->topic_id,
                    'no_of_correct_answers' => $sprint_statistic->no_of_correct_answers,
                    'points' => $sprint_statistic->points,
                    'best_time' => $sprint_statistic->best_time,
                    'completed' => $sprint_statistic->completed,
                    'has_cup' => $sprint_statistic->has_cup
                ];
            }
            $data['sprints_data'] = $sprint_data;
        }

        $marathon_statistics = ContextMarathonStatistic::where('user_id', auth()->id())->get();

        foreach ($marathon_statistics as $marathon_statistic){

            $marathon_data[] = [
                'context_id' => $marathon_statistic->context_id,
                'points' => $marathon_statistic->points,
                'bucket' => $marathon_statistic->bucket,
                'status' => $marathon_statistic->status,
            ];
        }
        $data['marathon_data'] = $marathon_data;
        return $data;
    }

 public function UserAllStatistics(){

     $data['user_info'] = $this->UserInfo();
     if(empty($game_id) && empty($topic_id) && empty($context_id)) {
         $data['all_statistics'] = $this->AllStatistics();
     }
     return $data;
 }
}