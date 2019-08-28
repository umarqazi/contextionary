<?php


namespace App\Services;


use App\ContextMarathonStatistic;
use App\User;
use App\UserCurrentContext;
use App\UserUnlockedContext;

class UserRecordService extends BaseService implements IService
{
 public function UserCurrentContext(){

     $current_context = UserCurrentContext::where('user_id', auth()->id())->first();
     $data[] = [
         'current_context_id' => $current_context->current_context_id,
         'last_played_phrase_id' => $current_context->last_phrase_id,
     ];
     return $data;
 }

 public function UserUnlockedContext(){

     $unlocked_context = UserUnlockedContext::where('user_id', auth()->id())->first();
     $data = $unlocked_context->unlocked_context;
     return $data;
 }

 public function MarathonStatistics(){

     $marathon_statistics = ContextMarathonStatistic::where('user_id', auth()->id())->get();
     foreach ($marathon_statistics as $marathon_statistic) {
         $data[] = [
             'context_id' => $marathon_statistic->context_id,
             'points' => $marathon_statistic->points,
             'bucket' => $marathon_statistic->bucket,
             'status' => $marathon_statistic->status,
         ];
     }
     return $data;
 }

 public function UserInfo(){

     $user_info = User::where('id', auth()->id())->first();
     $data[] = [
         'game_coins' => $user_info->game_coins,
         'aladdin_lamp' => $user_info->aladdin_lamp,
         'butterfly_effect' => $user_info->butterfly_effect,
     ];
     return $data;
 }

 public function ShowAllData(){
     $data['current_context'] = $this->UserCurrentContext();
     $data['unlocked_context'] = $this->UserUnlockedContext();
     $data['marathon_statistics'] = $this->MarathonStatistics();
     $data['user_info'] = $this->UserInfo();
     return $data;
 }
}