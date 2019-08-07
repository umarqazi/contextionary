<?php

namespace App\Http\Controllers\Api;

use App\ContextTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{

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

            return json('Topic List shown as:', 200,  $topics);
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
    public function generateContextTopics(Request $request) {

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
        $context_topics = $topics->with(['context', 'solPhrase', 'wrongPhrase'])->get();
        $context_topics = new Paginator($context_topics, $length);
        $batch = [];

        foreach ($context_topics as $key => $data) {
            ($key == 0) ? $batch['has_more'] = $context_topics->hasMorePages() : false;
            $batch['context_sprint'][] = [
                'id' => $data->id,
                'topic_id' => $data->topic_id,
                'bucket' => $data->bucket,
                'context_name' => $data->context->context_name ?? null,
                'phrase_sol' => $data->solPhrase->phrase_text ?? null,
                'phrase_wrong' => $data->wrongPhrase->phrase_text ?? null,
            ];
        }
        if($batch){
            $batch['context_sprint'] = array_values($batch['context_sprint']);
            return json('Data found:', 200, $batch);
        } else{
            return json('Data not found!', 400);
        }
    }

    public function generatePhraseTopics(Request $request){

        $validate = Validator::make($request->all(), [
            'bucket' => 'required|integer',
            'topic_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 200);
        }
        // Phrase Sprint
        $topics = \App\PhraseSprint::where(['bucket' => $request->bucket, 'topic_id' => $request->topic_id]);
        $length = $this->percentage($topics->count());
        $phrase_topics = $topics->with(['phrase', 'solContext', 'wrongContext'])->get();

        $phrase_topics = new Paginator($phrase_topics, $length);
        $batch = [];

        foreach ($phrase_topics as $key => $data) {
            ($key == 0) ? $batch['has_more'] = $phrase_topics->hasMorePages() : false;
            $batch['phrase_sprint'][] = [
                'id' => $data->id,
                'topic_id' => $data->topic_id,
                'bucket' => $data->bucket,
                'phrase_name' => $data->phrase->phrase_text ?? null,
                'context_sol' => $data->solContext->context_name ?? null,
                'context_wrong' => $data->wrongContext->context_name ?? null,
            ];
        }
        if($batch){
            $batch['phrase_sprint'] = array_values($batch['phrase_sprint']);
            return json('Data found:', 200, $batch);
        } else{
            return json('Data not found!', 400);
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
