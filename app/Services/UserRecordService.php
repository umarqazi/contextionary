<?php


namespace App\Services;


use App\Context;
use App\ContextMarathonStatistic;
use App\Setting;
use App\SprintStatistic;
use App\UnlockedRegionContext;
use App\UnlockedRoom;
use App\UnlockedSprintMystery;
use App\User;
use App\UserAttemptedQuestion;
use App\UserCurrentContext;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

class UserRecordService extends BaseService implements IService
{

    public $marathonstatisticservice;
    public function __construct()
    {
        $this->marathonstatisticservice = new MarathonStatisticService();
    }

    /**
     * @param $update_user_info
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdateUserInfo($update_user_info){

        $update_info = User::where('id', auth()->id())->first();
        if($update_info){

            $update_info->game_coins       = $update_user_info['game_coins'] ?? $update_info->game_coins;
            $update_info->aladdin_lamp     = $update_user_info['aladdin_lamp'] ?? $update_info->aladdin_lamp;
            $update_info->butterfly_effect = $update_user_info['butterfly_effect'] ?? $update_info->butterfly_effect;
            $update_info->stopwatch        = $update_user_info['stopwatch'] ?? $update_info->stopwatch;
            $update_info->time_traveller   = $update_user_info['time_traveller'] ?? $update_info->time_traveller;
            $update_info->learning_center  = $update_user_info['learning_center'] ?? $update_info->learning_center;
            $update_info->game_session     = $update_user_info['game_session'] ?? $update_info->game_session;
            $update_info->honour_badge     = $update_user_info['honour_badge'] ?? $update_info->honour_badge;
            $update_info->coins_earned     = $update_user_info['coins_earned'] ?? $update_info->coins_earned;
            $update_info->coins_purchased  = $update_user_info['coins_purchased'] ?? $update_info->coins_purchased;
            $update_info->coins_used       = $update_user_info['coins_used'] ?? $update_info->coins_used;

            $updated = $update_info->save();
            if($updated){
                return json('User info updated', 200);
            }else{
                return json('Something went wrong!', 400);
            }
        }
    }

    public function AddUserSprintStatistics($data){

        $msg = '';
        $code = '';
        $update_statistics = SprintStatistic::where(['user_id' => auth()->id(), 'game_id' => $data['game_id'], 'topic_id' => $data['topic_id']])->first();
        if($update_statistics){
            if($data['correct_answers'] > $update_statistics->no_of_correct_answers){

                $update_statistics->no_of_correct_answers = $data['correct_answers'];
            }

            if($data['points'] > $update_statistics->points){

                $update_statistics->points = $data['points'];
            }

            if($data['best_time'] < $update_statistics->best_time){

                $update_statistics->best_time = $data['best_time'];
            }

            $update_statistics->has_cup = $data['has_cup'];
            $update_statistics->completed = $data['completed'];
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
                'game_id' => $data['game_id'],
                'topic_id' => $data['topic_id'],
                'no_of_correct_answers' => $data['correct_answers'],
                'points' => $data['points'],
                'best_time' => $data['best_time'],
                'completed' => $data['completed'],
                'has_cup' => $data['has_cup']
            ]);
            if($sprint_statistics){
                $msg = 'Sprint statistic has inserted.';
                $code = '200';
            }else{
                $msg = 'Something wrong here!';
                $code = '400';
            }
        }

        $unlocked_sprint = SprintStatistic::where(['user_id' => auth()->id(), 'game_id' => $data['game_id'], 'has_cup' => 1])->first();

        if($unlocked_sprint) {

            $unlocked_sprint_id = $data['game_id']+1;

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

    /**
     * @param $user_attempted_questions
     * @return \Illuminate\Http\JsonResponse
     */
    public function UserAttemptedQuestions($user_attempted_questions){

        foreach ($user_attempted_questions['attempted_id'] as $questions_id){

            $attempt_questions_id[] = [
                'user_id' => auth()->id(),
                'game_id' => $user_attempted_questions['game_id'],
                'game_type' => $user_attempted_questions['game_type'] ?? null,
                'question_id' => $questions_id
            ];
        }

        $user_game_points = UserAttemptedQuestion::insert($attempt_questions_id);
        if($user_game_points){

            return json('User questions id have been saved successfully', 200);
        } else {

            return json('Something wrong here!', 400);
        }
    }

    public function AppVersion(){

        $version = Setting::where('keys', 'app_version')->orWhere('keys', 'android_link')->orWhere('keys', 'ios_link')->get();

        foreach ($version as $item) {
            $batch[$item->keys] = $item->values;
        }
        if($batch){

            return $batch;
        }else{

            return json('App version not found!', 400);
        }
    }

    public function contexts(){

        $contexts = Context::all();
        return $contexts;
    }

    public function MarathonStatistics($context_id){

        $marathon_statistics = ContextMarathonStatistic::where(['user_id' => auth()->id(), 'context_id' => $context_id])->first();

        if($marathon_statistics){

            $data[] = [
                'context_id' => $marathon_statistics->context_id,
                'points' => $marathon_statistics->points,
                'bucket' => $marathon_statistics->bucket,
                'answered_phrases' => $marathon_statistics->answered_phrases,
                'is_clear' => $marathon_statistics->is_clear,
                'butterfly_available' => $marathon_statistics->butterfly_available,
                'win_in_a_row' => (is_null($marathon_statistics->win_in_a_row) ? 0 : $marathon_statistics->win_in_a_row),
                'hint_in_a_row' => (is_null($marathon_statistics->hint_in_a_row) ? 0 : $marathon_statistics->hint_in_a_row)
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
            'learning_center' => $user_info->learning_center,
            'game_session' => $user_info->game_session,
            'honour_badge' => $user_info->honour_badge,
            'coins_earned' => $user_info->coins_earned,
            'coins_purchased' => $user_info->coins_purchased,
            'coins_used' => $user_info->coins_used
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

    public function UserGameRecords($game_id=null, $topic_id=null, $context_id=null){

        $data['marathon_records'] = $this->MarathonStatistics($context_id);
        $data['user_info'] = $this->UserInfo();
        $data['sprint_records'] = $this->SprintStatistics($game_id, $topic_id);
        return $data;
    }

    public function IncompleteMaxPoints(){
        $incomplete_contexts = ContextMarathonStatistic::select('points', 'context_id')
            ->where(
                [
                    'user_id' => auth()->id(),
                    'is_clear' => 0
                ]
            )
            ->where('points', '>', 0)
            ->orderBy('points', 'desc')
            ->take(3)
            ->get();
        if(!$incomplete_contexts->isEmpty()) {
            foreach ($incomplete_contexts as $incomplete_context) {
                $incomplete_context_points[] = [
                    'Points' => $incomplete_context->points,
                    'Context_id' => $incomplete_context->context_id,
                ];
            }
            return $incomplete_context_points;
        }
    }

    public function CurrentContextMarathon(){

        $user_context = UserCurrentContext::where('user_id', auth()->id())->first();
        $user_regions = UnlockedRegionContext::select('region_id', DB::raw('count(*) as total'))->where('user_id', auth()->id())->groupBy('region_id')->orderBy('total', 'desc')->pluck('region_id');
        $user_regions_1 = 0;
        $user_regions_2 = 0;
        $user_regions_3 = 0;
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
    public function SprintHasCups($game_id){

        $sprints_statistics = SprintStatistic::select('game_id', DB::raw('count(*) as total'))
            ->where(['user_id' => auth()->id(), 'game_id' => $game_id, 'has_cup' => 1])
            ->groupBy('game_id')
            ->orderBy('total', 'desc')
            ->get()->toArray();
        return $sprints_statistics;
    }

    public function SprintsRecords(){

        $max_points = SprintStatistic::select('points', 'game_id')->where('user_id', auth()->id())->get()->groupBy('game_id')->toArray();
        $max_answers = SprintStatistic::select('no_of_correct_answers', 'game_id')->where('user_id', auth()->id())->get()->groupBy('game_id')->toArray();
        $best_time = SprintStatistic::select('best_time', 'game_id')->where('user_id', auth()->id())->get()->groupBy('game_id')->toArray();

        if(!empty($max_points) && !empty($max_answers) && !empty($best_time)) {

            $results['max_points'] = collect($max_points)->map(function ($result, $index) {
                return collect($result)->max('points');
            });

            $results['max_answers'] = collect($max_answers)->map(function($result, $index) {
                return collect($result)->max('no_of_correct_answers');
            });

            $results['best_time'] = collect($best_time)->map(function($result, $index) {
                return collect($result)->min('best_time');
            });
        } else{

            $results['max_points'] = [-1 => -1];
            $results['max_answers'] = [-1 => -1];
            $results['best_time'] = [-1 => -1];
        }
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

        $unlocked_mystery_topics = UnlockedRoom::select('room_id', 'door_id')->where('user_id', auth()->id())->get();
        return $unlocked_mystery_topics;
    }
    public function UnlockedContexts(){

        $unlocked_mystery_topics = UnlockedRegionContext::select('unlocked_context', 'region_id')->where('user_id', auth()->id())->get();
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
        $data['incomplete_max_points'] = $this->IncompleteMaxPoints();
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
        $data['unlocked_contexts'] = $this->UnlockedContexts();
        return $data;
    }

    public function UserMarathonStatistics($data){

        $data['add_marathon_statistics'] = $this->marathonstatisticservice->AddMarathonStatistics($data);
        if(array_key_exists('data', $data)){

            $data['unlocked_marathon_rooms'] = $this->marathonstatisticservice->UnlockedRooms($data['data']);
            $data['unlocked_marathon_region_contexts'] = $this->marathonstatisticservice->UnlockedRegionContexts($data['data']);
        }
        $data['last_played_marathon_statistics'] = $this->marathonstatisticservice->LastPlayedMarathonRecord($data);
        $data['update_user_info'] = $this->UpdateUserInfo($data);
        if(array_key_exists('attempted_id', $data)){

            $data['UserAttemptedQuestions'] = $this->UserAttemptedQuestions($data);
        }
        return $data;
    }

    public function UserSprintStatistics($data){

        $data['UserSprintStatistic'] = $this->AddUserSprintStatistics($data);
        $data['update_user_info'] = $this->UpdateUserInfo($data);
        if(array_key_exists('attempted_id', $data)){

            $data['UserAttemptedQuestions'] = $this->UserAttemptedQuestions($data);
        }
        return $data;
    }

    public function UserGameLoad(){

        $data['user_info'] = $this->UserInfo();
        $data['context_marathon'] = $this->CurrentContextMarathon();
        $data['unlocked_sprints'] = $this->UnlockedSprints();
        $data['unlocked_mystery_topics'] = $this->UnlockedMysteryTopics();
        $data['unlocked_rooms'] = $this->UnlockedRooms();
        $data['unlocked_contexts'] = $this->UnlockedContexts();
        $data['app_version'] = $this->AppVersion();
        $data['contexts'] = $this->contexts();
        return $data;
    }
}