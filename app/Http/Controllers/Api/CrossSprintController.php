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
        $cross_sprints = CrossSprint::where(['topic_id' => $request->topic_id])->whereNotIn('id', $attempt_question)
            ->with(['hint_phrase_1', 'hint_phrase_2', 'hint_phrase_3', 'hint_phrase_4'])->get();
        $sprintCups['sprint_cups'] = $this->userrecordservice->SprintHasCups($request->game_id);

        $batch = [];
        $batch['sprint_cups'] = $sprintCups['sprint_cups'];

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
            $batch['cross_sprint'] = array_values($batch['cross_sprint']);
            return json('Cross sprint data shown as:', 200, $batch);
        } else{
            return json('Data not found!', 400);
        }
    }
}
