<?php

namespace App\Http\Controllers\Api;

use App\CrossSprint;
use App\Services\UserRecordService;
use App\UserAttemptedQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CrossSprintController extends Controller
{

    public $userrecordservice;
    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
    }

    public function cross_sprint_game(Request $request){

        $validate = Validator::make($request->all(), [
            'topic_id' => 'required|integer',
            'game_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 400);
        }

        $attempted = UserAttemptedQuestion::where(['user_id' => auth()->id(), 'game_id' => $request->game_id])->select('question_id')->get();
        $attempt_question = [];
        foreach ($attempted as $attempt) {
            $attempt_question[] = $attempt->question_id;
        }
        $cross_sprints1 = CrossSprint::where(['topic_id' => $request->topic_id, 'bucket' => 1])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $cross_sprints2 = CrossSprint::where(['topic_id' => $request->topic_id, 'bucket' => 2])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $cross_sprints3 = CrossSprint::where(['topic_id' => $request->topic_id, 'bucket' => 3])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $cross_sprints4 = CrossSprint::where(['topic_id' => $request->topic_id, 'bucket' => 4])->whereNotIn('id', $attempt_question)->limit(75)->get();

        $cross_sprint = $cross_sprints1->merge($cross_sprints2)->merge($cross_sprints3)->merge($cross_sprints4);
        $cross_sprints = $cross_sprint->load(['hint_phrase_1', 'hint_phrase_2', 'hint_phrase_3', 'hint_phrase_4']);
        $sprintCups['sprint_cups'] = $this->userrecordservice->SprintHasCups($request->game_id);
        $sprintCups['sprint_records'] = $this->userrecordservice->sprintTopicRecords($request->topic_id, $request->game_id);
        $sprintCups['user_info'] = $this->userrecordservice->UserInfo();

        $batch = [];
        $batch['sprint_cups'] = $sprintCups['sprint_cups'];
        $batch['sprint_records'] = $sprintCups['sprint_records'];
        $batch['user_info'] = $sprintCups['user_info'];

        foreach ($cross_sprints as $key => $data) {
            //($key == 0) ? $batch['has_more'] = $cross_sprints->hasMorePages() : false;
            $batch['cross_sprint'][] = [
                'id' => $data->id ?? null,
                'topic_id' => $data->topic_id ?? null,
                'bucket_level' => $data->bucket ?? null,
                'solution_puzzle_word' => $data->puzzle_word ?? null,
                'primary_hint_phrase' => $data->hint_phrase_1->phrase_text ?? null,
                'hint_phrase_2' => $data->hint_phrase_2->phrase_text ?? null,
                'hint_phrase_3' => $data->hint_phrase_3->phrase_text ?? null,
                'hint_phrase_4' => $data->hint_phrase_4->phrase_text ?? null,
            ];
        }
        if($batch){
            $batch['cross_sprint'] = (!$cross_sprints->isEmpty()) ? array_values($batch['cross_sprint']) : [];
            return json('Cross sprint data shown as:', 200, $batch);
        } else{
            return json('Data not found!', 400);
        }
    }
}
