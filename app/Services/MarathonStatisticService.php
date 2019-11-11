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
    public function UnlockedRooms($unlocked_rooms){

        if(array_key_exists('room_id', $unlocked_rooms)) {

            $unlocked_room_data = [];
            foreach ($unlocked_rooms as $unlocked_room) {

                $check_if_exist = UnlockedRoom::where([
                    'user_id' => auth()->id(),
                    'room_id' => $unlocked_room['room_id'],
                    'door_id' => $unlocked_room['door_id'],
                ])->exists();

                if (!$check_if_exist) {
                    $unlocked_room_data[] = [
                        'user_id' => auth()->id(),
                        'room_id' => $unlocked_room['room_id'],
                        'door_id' => $unlocked_room['door_id']
                    ];
                }
            }
            $insert_unlocked_room = UnlockedRoom::insert($unlocked_room_data);

            if($insert_unlocked_room){
                return json('Unlocked rooms has been saved successfully', 200);
            }else{
                return json('Something wrong here!', 400);
            }
        }
    }

    public function UnlockedRegionContexts($context_marathon_stats){
        if(array_key_exists('unlocked_context', $context_marathon_stats)) {

            $unlocked_context = [];
            foreach ($context_marathon_stats as $unlocked_context_region) {

                $check_if_exist = UnlockedRegionContext::where([
                    'user_id' => auth()->id(),
                    'unlocked_context' => $unlocked_context_region['unlocked_context'],
                    'region_id' => $unlocked_context_region['region_id'],
                ])->exists();

                if (!$check_if_exist) {

                    $unlocked_context[] = [
                        'user_id' => auth()->id(),
                        'unlocked_context' => $unlocked_context_region['unlocked_context'],
                        'region_id' => $unlocked_context_region['region_id']
                    ];
                }
            }
            $insert_unlocked_context = UnlockedRegionContext::insert($unlocked_context);

            if($insert_unlocked_context){
                return json('Unlocked context has been saved successfully', 200);
            }else{
                return json('Something wrong here!', 400);
            }
        }
    }

    public function AddMarathonStatistics($context_id, $points, $bucket, $answered, $is_clear, $butterfly_available){

        $marathon_stats = ContextMarathonStatistic::where(['user_id' => auth()->id(), 'context_id' => $context_id])->first();
        if($marathon_stats){

            $marathon_stats->points = $points;
            $marathon_stats->bucket = $bucket;
            $marathon_stats->answered_phrases = $answered;
            $marathon_stats->is_clear = $is_clear;
            $marathon_stats->butterfly_available = $butterfly_available;
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
                'is_clear' => $is_clear,
                'butterfly_available' => $butterfly_available
            ]);
            if($marathon_statistics){

                return json('Status has been saved successfully.', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }
    }

    public function AddUserAllMarathonStatistics($context_marathon_stats){

        $unlocked_region_context = [];
        $unlocked_rooms = [];

        $user_id = auth()->id();

        if(array_key_exists('data', $context_marathon_stats )) {
            foreach ($context_marathon_stats['data'] as $stat_data) {

                if(array_key_exists('unlocked_context',  $stat_data) && array_key_exists('region_id', $stat_data)) {

                    $region_context['unlocked_context'] = $stat_data['unlocked_context'];
                    $region_context['region_id'] = $stat_data['region_id'];
                    $region_context['user_id'] = $user_id;

                    $unlocked_region_context[] = $region_context;
                }

                if(array_key_exists('room_id',  $stat_data) && array_key_exists('door_id', $stat_data)) {

                    $unlocked_room_data['room_id'] = $stat_data['room_id'];
                    $unlocked_room_data['door_id'] = $stat_data['door_id'];
                    $unlocked_room_data['user_id'] = $user_id;

                    $unlocked_rooms[] = $unlocked_room_data;
                }
            }
        }
        $data['unlocked_region_context'] = $this->UnlockedRegionContexts($unlocked_region_context);
        $data['unlocked_rooms'] = $this->UnlockedRooms($unlocked_rooms);
        $data['context_stats'] = $this->AddMarathonStatistics($context_marathon_stats['context_id'], $context_marathon_stats['points'], $context_marathon_stats['bucket'], $context_marathon_stats['answered_phrases'], $context_marathon_stats['is_clear'], $context_marathon_stats['butterfly_available']);
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
                'top_maze_level' => $last_played_marathon_record['top_maze_level']
            ]);
            if($add_last_played_marathon){

                return json('Status has been saved successfully.', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }
    }
}