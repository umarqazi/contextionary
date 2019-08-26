<?php


namespace App\Services;


use App\UserCurrentContext;

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

 public function ShowAllData(){
     $data['current_context'] = $this->UserCurrentContext();
     return $data;
 }
}