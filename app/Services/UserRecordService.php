<?php


namespace App\Services;


use App\ContextMarathonStatistic;
use App\SprintStatistic;
use App\UnlockedRegionContext;
use App\UnlockedRoom;
use App\UnlockedSprintMystery;
use App\User;
use App\UserCurrentContext;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

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


    public function CurrentContextMarathon(){

        $user_context = UserCurrentContext::where('user_id', auth()->id())->first();
        $user_regions = UnlockedRegionContext::select('region_id', DB::raw('count(*) as total'))->where('user_id', auth()->id())->groupBy('region_id')->orderBy('total', 'desc')->pluck('region_id');
        $user_regions_1 = '';
        $user_regions_2 = '';
        $user_regions_3 = '';
        if(!$user_regions->isEmpty()) {
            if (isset($user_regions[0])) {
                $user_regions_1 = $user_regions[0];
            }
            if (isset($user_regions[1])) {
                $user_regions_2 = $user_regions[1];
            }
            if (isset($user_regions[2])) {
                $user_regions_3 = $user_regions[2];
            }
        }
        if($user_context) {
            $data[] = [
                'current_context_id' => $user_context->current_context_id,
                'last_played_phrase_id' => $user_context->last_played_phrase_id,
                'last_played_cell' => $user_context->last_played_cell,
                'top_maze_level' => $user_context->top_maze_level,
                'max_unlocked_context' => $user_context->unlocked_context,
                'learning_center' => $user_context->learning_center,
                'First_region' => $user_regions_1,
                'Second_region' => $user_regions_2,
                'Third_region' => $user_regions_3
            ];
            return $data;
        }
    }

    public function SprintCups(){

        $sprints_statistics = SprintStatistic::select('game_id', DB::raw('count(*) as total'))
            ->where(['user_id' => auth()->id(), 'has_cup' => 1])
            ->groupBy('game_id')
            ->orderBy('total', 'desc')
            ->get()->toArray();
        return $sprints_statistics;
    }

    public function SprintsRecords(){

        $max_points = SprintStatistic::select('points', 'game_id')->where('user_id', auth()->id())->get()->groupBy('game_id')->toArray();
        $max_answers = SprintStatistic::select('no_of_correct_answers', 'game_id')->where('user_id', auth()->id())->get()->groupBy('game_id')->toArray();
        $best_time = SprintStatistic::select('best_time', 'game_id')->where('user_id', auth()->id())->get()->groupBy('game_id')->toArray();

        $results['max_points'] = collect($max_points)->map(function($result, $index) {
            return collect($result)->max('points');
        });

        $results['max_answers'] = collect($max_answers)->map(function($result, $index) {
            return collect($result)->max('no_of_correct_answers');
        });

        $results['best_time'] = collect($best_time)->map(function($result, $index) {
            return collect($result)->min('best_time');
        });
        return $results;
    }

    public function UnlockedSprints(){

        $unlocked_sprints = UnlockedSprintMystery::select('unlocked_sprint')->where('user_id', auth()->id())->whereNotNull('unlocked_sprint')->get()->toArray();
        return $unlocked_sprints;
    }

    public function UnlockedMysteryTopics(){

        $unlocked_mystery_topics = UnlockedSprintMystery::select('unlocked_mystery_topic')->where('user_id', auth()->id())->whereNotNull('unlocked_mystery_topic')->get()->toArray();
        return $unlocked_mystery_topics;
    }

    public function UnlockedRooms(){

        $unlocked_mystery_topics = UnlockedRoom::select('room_id', 'door_id')->where('user_id', auth()->id())->get()->groupBy('room_id');
        $unlocked_mystery_topics = array_values(collect($unlocked_mystery_topics)->toArray());
        return $unlocked_mystery_topics;
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

        if(!$marathon_statistics->isEmpty()) {
            foreach ($marathon_statistics as $marathon_statistic) {

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
    }

    public function UserAllStatistics(){

        $data['user_info'] = $this->UserInfo();
        $data['context_marathon'] = $this->CurrentContextMarathon();
        $data['sprint_cups'] = $this->SprintCups();
        $data['sprints_records'] = $this->SprintsRecords();
        return $data;
    }

    public function UserAppLoad(){

        $data['user_info'] = $this->UserInfo();
        $data['context_marathon'] = $this->CurrentContextMarathon();
        $data['unlocked_sprints'] = $this->UnlockedSprints();
        $data['unlocked_mystery_topics'] = $this->UnlockedMysteryTopics();
        $data['unlocked_rooms'] = $this->UnlockedRooms();
        return $data;
    }
}