<?php

namespace App\Http\Controllers\Api;

use App\ContextTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     *
    @SWG\Post(
     *     path="/topic_list",
     *     description="Context Topics List",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    function topic_list(){

        $topics = ContextTopic::all();
        if($topics){

            return response()->json(['Data' => $topics]);
        }
    }

    /**
     *
    @SWG\Post(
     *     path="/context_sprint",
     *     description="Context Sprint List",
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
    public function generateTopics(Request $request) {

        $validate = Validator::make($request->all(), [
            'bucket' => 'required|integer',
            'topic_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 200);
        }
        // Context Sprint
        $topics = \App\ContextSprint::where(['bucket' => $request->bucket, 'topic_id' => $request->topic_id]);
        $length = $this->percentage($topics->count());
        $context_topics = $topics->with(['context', 'solPhrase', 'wrongPhrase'])
            ->inRandomOrder()
            ->get();
        $context_topics = new Paginator($context_topics, $length);
        // Phrase Sprint
        $topics = \App\PhraseSprint::where(['bucket' => $request->bucket, 'topic_id' => $request->topic_id]);
        $length = $this->percentage($topics->count());
        $phrase_topics = $topics->with(['phrase', 'solContext', 'wrongContext'])
            ->inRandomOrder()
            ->get();

        $phrase_topics = new Paginator($phrase_topics, $length);
        $batch = [];

        foreach ($context_topics as $key => $data) {
            ($key == 0) ? $batch['context_sprint']['has_more'] = $context_topics->hasMorePages() : false;
            $batch['context_sprint'][] = [
                'id' => $data->id,
                'context_name' => $data->context->context_name ?? null,
                'phrase_sol' => $data->solPhrase->phrase_text ?? null,
                'phrase_wrong' => $data->wrongPhrase->phrase_text ?? null,
            ];
        }

        foreach ($phrase_topics as $key => $data) {
            ($key == 0) ? $batch['phrase_sprint']['has_more'] = $phrase_topics->hasMorePages() : false;
            $batch['phrase_sprint'][] = [
                'id' => $data->id,
                'phrase_name' => $data->phrase->phrase_text ?? null,
                'context_sol' => $data->solContext->context_name ?? null,
                'context_wrong' => $data->wrongContext->context_name ?? null,
            ];
        }

        return json('data found', '200', $batch);
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
