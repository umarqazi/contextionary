<?php

namespace App\Http\Controllers\Api;

use App\CrossSprint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CrossSprintController extends Controller
{
    public function cross_sprint_game(Request $request){

        $validate = Validator::make($request->all(), [
            'bucket' => 'required|integer',
            'topic_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 400);
        }

        $cross_sprints = CrossSprint::where(['bucket' => $request->bucket, 'topic_id' => $request->topic_id])
            ->with(['hint_phrase_1', 'hint_phrase_2', 'hint_phrase_3', 'hint_phrase_4'])->inRandomOrder()->paginate(20);
        $batch = [];

        foreach ($cross_sprints as $key => $data) {
            ($key == 0) ? $batch['cross_sprint']['has_more'] = $cross_sprints->hasMorePages() : false;
            $batch['cross_sprint'][] = [
                'bucket_level' => $data->bucket ?? null,
                'solution_puzzle_word' => $data->puzzle_word ?? null,
                'primary_hint_phrase' => $data->hint_phrase_1->phrase_text ?? null,
                'hint_phrase_2' => $data->hint_phrase_2->phrase_text ?? null,
                'hint_phrase_3' => $data->hint_phrase_3->phrase_text ?? null,
                'hint_phrase_4' => $data->hint_phrase_4->phrase_text ?? null,
            ];
        }
        if($batch){
            return json('Cross sprint data shown as:', 200, $batch);
        } else{
            return json('Data not found!', 400);
        }
    }
}