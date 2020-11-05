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

        if($unlocked_rooms) {

            $unlocked_room_data = [];
            foreach ($unlocked_rooms as $unlocked_room) {

                if(array_key_exists('room_id', $unlocked_room)) {

                    $check_if_exist = UnlockedRoom::where([
                        'user_id' => auth()->id(),
                        'room_id' => $unlocked_room['room_id'],
                        'door_id' => array_key_exists('door_id', $unlocked_room) ? $unlocked_room['door_id'] : null
                    ])->exists();

                    if (!$check_if_exist) {
                        $unlocked_room_data[] = [
                            'user_id' => auth()->id(),
                            'room_id' => $unlocked_room['room_id'],
                            'door_id' => array_key_exists('door_id', $unlocked_room) ? $unlocked_room['door_id'] : null
                        ];
                    }
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
        if($context_marathon_stats) {

            $unlocked_context = [];
            foreach ($context_marathon_stats as $unlocked_context_region) {

                if(array_key_exists('unlocked_context', $unlocked_context_region)) {

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
            }
            $insert_unlocked_context = UnlockedRegionContext::insert($unlocked_context);

            if($insert_unlocked_context){
                return json('Unlocked context has been saved successfully', 200);
            }else{
                return json('Something wrong here!', 400);
            }
        }
    }

    public function AddMarathonStatistics($data){

        $marathon_stats = ContextMarathonStatistic::where(['user_id' => auth()->id(), 'context_id' => $data['context_id']])->first();
        if($marathon_stats){

            $marathon_stats->points = $data['points'] ?? $marathon_stats->points;
            $marathon_stats->bucket = $data['bucket'] ?? $marathon_stats->bucket;
            $marathon_stats->answered_phrases = $data['answered_phrases'] ?? $marathon_stats->answered_phrases;
            $marathon_stats->is_clear = $data['is_clear'] ?? $marathon_stats->is_clear;
            $marathon_stats->butterfly_available = $data['butterfly_available'] ?? $marathon_stats->butterfly_available;
            $marathon_stats->win_in_a_row = $data['win_in_a_row'] ?? $marathon_stats->win_in_a_row;
            $marathon_stats->hint_in_a_row = $data['hint_in_a_row'] ?? $marathon_stats->hint_in_a_row;
            $updated = $marathon_stats->save();
            if($updated){
                return json('User status has been updated', 200);
            }else{
                return json('Something went wrong!', 400);
            }
        } else {

            $marathon_statistics = ContextMarathonStatistic::create([

                'user_id' => auth()->id(),
                'context_id' => $data['context_id'] ?? null,
                'points' => $data['points'] ?? null,
                'bucket' => $data['bucket'] ?? null,
                'answered_phrases' => $data['answered_phrases'] ?? 0,
                'is_clear' => $data['is_clear'] ?? null,
                'butterfly_available' => $data['butterfly_available'] ?? null,
                'win_in_a_row' => $data['win_in_a_row'] ?? null,
                'hint_in_a_row' => $data['hint_in_a_row'] ?? null
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
        $data['context_stats'] = $this->AddMarathonStatistics($context_marathon_stats);
        return $data;
    }

    public function LastPlayedMarathonRecord($last_played_marathon_record){

        $update = UserCurrentContext::where([
            'user_id' => auth()->id(),
            'current_context_id' => $last_played_marathon_record['current_context_id']
        ])->first();
        if($update){

            $data = [
                'current_context_id' => $last_played_marathon_record['current_context_id'],
                'last_played_phrase_id' => $last_played_marathon_record['last_played_phrase_id'],
                'last_played_cell' => $last_played_marathon_record['last_played_cell'],
                'unlocked_context' => $last_played_marathon_record['max_unlocked_context'],
                'top_maze_level' => $last_played_marathon_record['top_maze_level'],
                'no_of_hints_used' => $last_played_marathon_record['no_of_hints_used'],
                'crystal_ball_used' => $last_played_marathon_record['crystal_ball_used'],
                'normal_hint_count' =>
                    $last_played_marathon_record['current_context_normal_hint_count'] ?? $update->normal_hint_count,
                'golden_revealer' =>
                    $last_played_marathon_record['current_context_golden_revealer'] ?? $update->golden_revealer,
                'golden_revealer_count' =>
                    $last_played_marathon_record['current_context_golden_revealer_count'] ?? $update->golden_revealer_count,
                'diamond_revealer' =>
                    $last_played_marathon_record['current_context_diamond_revealer'] ?? $update->diamond_revealer,
                'golden_hints' =>
                    $last_played_marathon_record['current_context_golden_hints'] ?? $update->golden_hints,
                'diamond_hints' =>
                    $last_played_marathon_record['current_context_diamond_hints'] ?? $update->diamond_hints,
                'normal_revealer_usage_count' =>
                    $last_played_marathon_record['current_context_normal_revealer_usage_count'] ?? $update->normal_revealer_usage_count,
            ];
            $updated = UserCurrentContext::where('id', $update->id)->update($data);
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
                'no_of_hints_used' => $last_played_marathon_record['no_of_hints_used'],
                'crystal_ball_used' => $last_played_marathon_record['crystal_ball_used']
            ]);
            if($add_last_played_marathon){

                return json('Status has been saved successfully.', 200);
            }else{

                return json('Something wrong here!', 400);
            }
        }
    }
}
