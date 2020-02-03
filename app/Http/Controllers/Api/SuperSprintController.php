<?php

namespace App\Http\Controllers\Api;

use App\SuperSprint;
use App\UserAttemptedQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SuperSprintController extends Controller
{
    public function superSprint(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'topic_id' => 'required|integer',
            'game_id' => 'required|integer'
        ]);
        if($validate->fails())
        {
            return json($validate->errors(), 400);
        }
        //check user attempted questions
        $attempted = UserAttemptedQuestion::where(['user_id' => auth()->id(), 'game_id' => $request->game_id])->select('question_id')->get();
        $attempt_question = [];
        foreach ($attempted as $attempt) {
            $attempt_question[] = $attempt->question_id;
        }

        //get super sprint questions
        $super1 = SuperSprint::where(['topic_id' => $request->topic_id, 'bucket' => 1])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $super2 = SuperSprint::where(['topic_id' => $request->topic_id, 'bucket' => 2])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $super3 = SuperSprint::where(['topic_id' => $request->topic_id, 'bucket' => 3])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $super4 = SuperSprint::where(['topic_id' => $request->topic_id, 'bucket' => 4])->whereNotIn('id', $attempt_question)->limit(75)->get();

        $super = $super1->merge($super2)->merge($super3)->merge($super4);
        $super_sprints = $super->load(['topic', 'wrong_phrase_id', 'phrase']);

        $batch = [];

        foreach ($super_sprints as $key => $data) {

            $batch['super_sprint'][] = [
                'id' => $data->id ?? null,
                'topic_id' => $data->topic_id,
                'bucket' => $data->bucket,
                'hint' => $data->hint ?? null,
                'incorrect_phrase_id' => $data->wrong_phrase_id->phrase_text ?? null,
                'phrase' => $data->phrase->phrase_text ?? null,
            ];
        }
        if($batch) {

            $batch['super_sprint'] = array_values($batch['super_sprint']);
            return json('Super Sprints are:', 200, $batch);
        } else{

            return json('Data not found related to given information!', 400);
        }
    }
}
