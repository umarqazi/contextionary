<?php

namespace App\Http\Controllers\Api;

use App\ClueSprint;
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
            'topic_id' => 'required|integer'
        ]);
        if($validate->fails()){

            return json($validate->error(), 200);
        }

        $clues = ClueSprint::where(['bucket' => $request->bucket, 'topic_id' => $request->topic_id]);
        $length = $this->percentage($clues->count());
        $clues_sprints = $clues->with(['context', 'phrase', 'wrong_replacement_id_1', 'wrong_replacement_id_2', 'wrong_replacement_id_3'])
            ->inRandomOrder()
            ->get();
        $clues_sprints = new Paginator($clues_sprints, $length);

        $batch = [];

        foreach ($clues_sprints as $key => $data) {

            ($key == 0) ? $batch['clue_sprint']['has_more'] = $clues_sprints->hasMorePages() : false;
            $batch['clue_sprint'][] = [
                'id' => $data->id,
                'context_name' => $data->context->context_name ?? null,
                'word_to_replace' => $data->word_to_replace,
                'phrase' => $data->phrase->phrase_text ?? null,
                'replacement_id_1' => $data->wrong_replacement_id_1->phrase_text ?? null,
                'replacement_id_2' => $data->wrong_replacement_id_2->phrase_text ?? null,
                'replacement_id_3' => $data->wrong_replacement_id_3->phrase_text ?? null,
            ];
        }
        if($batch) {

            return json('Clue Sprints are:', 200, $batch);
        } else{

            return json('Data not found related to given information!', 400);
        }
    }

    private function percentage($length) {
        if($length > 100) {
            $length *= PERCENTAGE;
            $length /= 100;
        } else {
            $length = 20;
        }

        return $length;
    }
}
