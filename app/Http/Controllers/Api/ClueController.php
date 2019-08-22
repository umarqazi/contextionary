<?php

namespace App\Http\Controllers\Api;

use App\ClueSprint;
use App\UserAttemptedQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class ClueController extends Controller
{
    /**
     *
    @SWG\Post(
     *     path="/clue_sprint",
     *     description="Clue Sprint List",
     *
    @SWG\Parameter(
     *         name="bucket",
     *         in="formData",
     *         type="string",
     *         description="Enter Bucket level",
     *         required=true,
     *     ),
     *
    @SWG\Parameter(
     *         name="topic_id",
     *         in="formData",
     *         type="string",
     *         description="Enter topic id",
     *         required=true,
     *     ),
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    public function clue_sprint(Request $request){

        $validate = Validator::make($request->all(), [
            'bucket' => 'required|integer',
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
        $clues = ClueSprint::where(['bucket' => $request->bucket, 'topic_id' => $request->topic_id])->whereNotIn('id', $attempt_question);
        $clues_sprints = $clues->with(['context', 'phrase', 'wrong_replacement_id_1', 'wrong_replacement_id_2', 'wrong_replacement_id_3'])
            ->paginate(20);

        $batch = [];

        foreach ($clues_sprints as $key => $data) {

            ($key == 0) ? $batch['has_more'] = $clues_sprints->hasMorePages() : false;
            $batch['clue_sprint'][] = [
                'id' => $data->id ?? null,
                'topic_id' => $data->topic_id,
                'bucket' => $data->bucket,
                'context_name' => $data->context->context_name ?? null,
                'word_to_replace' => $data->word_to_replace,
                'phrase' => $data->phrase->phrase_text ?? null,
                'replacement_id_1' => $data->wrong_replacement_id_1->phrase_text ?? null,
                'replacement_id_2' => $data->wrong_replacement_id_2->phrase_text ?? null,
                'replacement_id_3' => $data->wrong_replacement_id_3->phrase_text ?? null,
            ];
        }
        if($batch) {
            $batch['clue_sprint'] = array_values($batch['clue_sprint']);
            return json('Clue Sprints are:', 200, $batch);
        } else{

            return json('Data not found related to given information!', 400);
        }
    }
}
