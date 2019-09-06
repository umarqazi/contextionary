<?php


namespace App\Services;


use App\ContextMarathonStatistic;
use App\UnlockedRegionContext;
use App\UnlockedRoom;
use App\User;
use App\UserCurrentContext;
use http\Env\Request;
use Illuminate\Support\Facades\Validator;

class MarathonStatisticService extends BaseService implements IService
{
    public function UnlockedRooms($room_id, $door_id){

        $update = UnlockedRoom::where(['user_id' => auth()->id(), 'room_id' => $room_id, 'door_id' => $door_id])->first();
        if($update){

            return json('Record already exist', 200);
        } else {

            $unlocked_rooms = UnlockedRoom::create([
                'user_id' => auth()->id(),
                'room_id' => $room_id,
                'door_id' => $door_id
            ]);
            if($unlocked_rooms){
                return json('Unlocked context has been saved successfully', 200);
            }else{
                return json('Something wrong here!', 400);
            }
        }
    }

    public function UnlockedRegionContexts($unlocked_context_id, $region_id){

        $update = UnlockedRegionContext::where(['user_id' => auth()->id(), 'unlocked_context' => $unlocked_context_id, 'region_id' => $region_id])->first();
        if($update){

            return json('Record already exist', 200);
        } else {

            $unlocked_context = UnlockedRegionContext::create([
                'user_id' => auth()->id(),
                'unlocked_context' => $unlocked_context_id,
                'region_id' => $region_id
            ]);
            if($unlocked_context){
                return json('Unlocked context has been saved successfully', 200);
            }else{
                return json('Something wrong here!', 400);
            }
        }
    }

    public function AddMarathonStatistics($context_id, $points, $bucket, $answered, $is_clear){

        $marathon_stats = ContextMarathonStatistic::where(['user_id' => auth()->id(), 'context_id' => $context_id])->first();
        if($marathon_stats){

            $marathon_stats->points = $points;
            $marathon_stats->bucket = $bucket;
            $marathon_stats->answered_phrases = $answered;
            $marathon_stats->is_clear = $is_clear;
            $updated = $marathon_stats->save();
            if($updated){
                return json('User status has been updated', 200);
            }else{
                return json('Something went wrong!', 400);
            }
        } else {

            $marathon_statistics = ContextMarathonStatistic::create([

                'user_id' => auth()->id(),
                'context_id' => $context_id,
                'points' => $points,
                'bucket' => $bucket,
                'answered_phrases' => $answered,
                'is_clear' => $is_clear
            ]);
            if($marathon_statistics){

                return json('Status has been saved successfully.', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }
    }

    public function AddUserAllMarathonStatistics($context_marathon_stats){

        $data['unlocked_rooms'] = $this->UnlockedRooms($context_marathon_stats['room_id'], $context_marathon_stats['door_id']);
        $data['marathon_unlocked'] = $this->UnlockedRegionContexts($context_marathon_stats['unlocked_context'], $context_marathon_stats['region_id']);
        $data['context_stats'] = $this->AddMarathonStatistics($context_marathon_stats['context_id'], $context_marathon_stats['points'], $context_marathon_stats['bucket'], $context_marathon_stats['answered_phrases'], $context_marathon_stats['is_clear']);
        return $data;
    }

    public function LastPlayedMarathonRecord($last_played_marathon_record){

        $update = UserCurrentContext::where('user_id', auth()->id())->first();
        if($update){

            $update->current_context_id = $last_played_marathon_record['current_context_id'];
            $update->last_played_phrase_id = $last_played_marathon_record['last_played_phrase_id'];
            $update->last_played_cell = $last_played_marathon_record['last_played_cell'];
            $update->unlocked_context = $last_played_marathon_record['max_unlocked_context'];
            $update->top_maze_level = $last_played_marathon_record['top_maze_level'];
            $update->learning_center = $last_played_marathon_record['learning_center'];
            $updated = $update->save();
            if($updated){
                return json('User last played record updated', 200);
            } else{
                return json('Something wrong here!', 400);
            }
        } else {

            $add_last_played_marathon = UserCurrentContext::create([
                'user_id' => auth()->id(),
                'current_context_id' => $last_played_marathon_record['current_context_id'],
                'last_played_phrase_id' => $last_played_marathon_record['last_played_phrase_id'],
                'last_played_cell' => $last_played_marathon_record['last_played_cell'],
                'unlocked_context' => $last_played_marathon_record['max_unlocked_context'],
                'top_maze_level' => $last_played_marathon_record['top_maze_level'],
                'learning_center' => $last_played_marathon_record['learning_center']
            ]);
            if($add_last_played_marathon){

                return json('Status has been saved successfully.', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }
    }
}