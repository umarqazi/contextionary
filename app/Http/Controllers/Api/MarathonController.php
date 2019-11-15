<?php

namespace App\Http\Controllers\Api;

use App\ContextMarathon;
use App\Services\UserRecordService;
use App\UserAttemptedQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MarathonController extends Controller
{
    public $userrecordservice;
    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
    }
    /**
     *
    @SWG\Post(
     *     path="/context_marathon",
     *     description="Context Marathon List",
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
     *         name="context_id",
     *         in="formData",
     *         type="string",
     *         description="Enter context id",
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
    public function context_marathon(Request $request){

        $validate = Validator::make($request->all(), [
            'bucket' => 'required|integer',
            'context_id' => 'required|integer',
            'game_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 200);
        }

        $attempted = UserAttemptedQuestion::where(['user_id' => auth()->id(), 'game_id' => $request->game_id])->select('question_id')->get();
        $attempt_question = [];
        foreach ($attempted as $attempt) {
            $attempt_question[] = $attempt->question_id;
        }
        $marathon = ContextMarathon::where(['bucket' => $request->bucket, 'context_id' => $request->context_id])->whereNotIn('id', $attempt_question);
        $context_marathon = $marathon->with('phrase')->get();
        $batch = [];

        foreach ($context_marathon as $key => $data){
            //($key == 0) ? $batch['has_more'] = $context_marathon->hasMorePages() : false;
            $batch['context_marathon'][] = [
                'id' => $data->id,
                'context_id' => $data->context_id ?? null,
                'phrase_id' => $data->phrase_id ?? null,
                'phrase_text' => $data->phrase->phrase_text ?? null,
                'bucket' => $data->bucket,
                'hint_1' => $data->hint_1,
                'hint_2' => $data->hint_2,
                'hint_3' => $data->hint_3,
                'hint_4' => $data->hint_4,
                'hint_5' => $data->hint_5,
                'hint_6' => $data->hint_6,
            ];
        }
        if($batch){

            if(!empty($request->context_id)){

                $batch['marathon_records'] = $this->userrecordservice->UserGameRecords(null, null, $request->context_id)['marathon_records'];
            }
            $batch['context_marathon'] = array_values($batch['context_marathon']);
            return json('Context marathon shown as:', 200, $batch);
        }else{

            return json('Data not found!', 400);
        }
    }
}
