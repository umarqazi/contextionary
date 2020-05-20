<?php

namespace App\Http\Controllers\Api;

use App\ContextTopic;
use App\Services\UserRecordService;
use App\PhraseSprint;
use App\ContextSprint;
use App\UserAttemptedQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{

    private $userrecordservice;
    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
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
            'topic_id' => 'required|integer',
            'game_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 200);
        }
        // Context Sprint
        $attempted = UserAttemptedQuestion::where(['user_id' => auth()->id(), 'game_id' => $request->game_id, 'game_type' => 2])->select('question_id')->get();
        $attempt_question = [];
        foreach ($attempted as $attempt) {
            $attempt_question[] = $attempt->question_id;
        }
        $topics1 = ContextSprint::where(['topic_id' => $request->topic_id, 'bucket' => 1])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $topics2 = ContextSprint::where(['topic_id' => $request->topic_id, 'bucket' => 2])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $topics3 = ContextSprint::where(['topic_id' => $request->topic_id, 'bucket' => 3])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $topics4 = ContextSprint::where(['topic_id' => $request->topic_id, 'bucket' => 4])->whereNotIn('id', $attempt_question)->limit(75)->get();

        $topics = $topics1->merge($topics2)->merge($topics3)->merge($topics4);
        $length = $this->percentage($topics->count());
        $context_topics = $topics->load(['context', 'solPhrase', 'wrongPhrase']);
        //$context_topics = new Paginator($context_topics, $length);
        $sprintCups['sprint_cups'] = $this->userrecordservice->SprintHasCups($request->game_id);
        $sprintCups['sprint_records'] = $this->userrecordservice->sprintTopicRecords($request->topic_id, $request->game_id);
        $sprintCups['user_info'] = $this->userrecordservice->UserInfo();

        $batch = [];
        $batch['sprint_cups'] = $sprintCups['sprint_cups'];
        $batch['sprint_records'] = $sprintCups['sprint_records'];
        $batch['user_info'] = $sprintCups['user_info'];

        foreach ($context_topics as $key => $data) {
            //($key == 0) ? $batch['has_more'] = $context_topics->hasMorePages() : false;
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
            $batch['context_sprint'] = (!$context_topics->isEmpty()) ? array_values($batch['context_sprint']) : [];
            return json('Data found:', 200, $batch);
        } else{
            return json('Data not found!', 400);
        }
    }

    public function generatePhraseTopics(Request $request){

        $validate = Validator::make($request->all(), [
            'topic_id' => 'required|integer',
            'game_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return json($validate->errors(), 200);
        }
        // Phrase Sprint
        $attempted = UserAttemptedQuestion::where(['user_id' => auth()->id(), 'game_id' => $request->game_id, 'game_type' => 1])->select('question_id')->get();
        $attempt_question = [];
        foreach ($attempted as $attempt) {
            $attempt_question[] = $attempt->question_id;
        }
        $topics1 = PhraseSprint::where(['topic_id' => $request->topic_id, 'bucket' => 1])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $topics2 = PhraseSprint::where(['topic_id' => $request->topic_id, 'bucket' => 2])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $topics3 = PhraseSprint::where(['topic_id' => $request->topic_id, 'bucket' => 3])->whereNotIn('id', $attempt_question)->limit(75)->get();
        $topics4 = PhraseSprint::where(['topic_id' => $request->topic_id, 'bucket' => 4])->whereNotIn('id', $attempt_question)->limit(75)->get();

        $topics = $topics1->merge($topics2)->merge($topics3)->merge($topics4);
        $length = $this->percentage($topics->count());
        $phrase_topics = $topics->load(['phrase', 'solContext', 'wrongContext']);
        //$phrase_topics = new Paginator($phrase_topics, $length);
        $sprintCups['sprint_cups'] = $this->userrecordservice->SprintHasCups($request->game_id);
        $sprintCups['sprint_records'] = $this->userrecordservice->sprintTopicRecords($request->topic_id, $request->game_id);
        $sprintCups['user_info'] = $this->userrecordservice->UserInfo();

        $batch = [];
        $batch['sprint_cups'] = $sprintCups['sprint_cups'];
        $batch['sprint_records'] = $sprintCups['sprint_records'];
        $batch['user_info'] = $sprintCups['user_info'];

        foreach ($phrase_topics as $key => $data) {
            //($key == 0) ? $batch['has_more'] = $phrase_topics->hasMorePages() : false;
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
            $batch['phrase_sprint'] = (!$phrase_topics->isEmpty()) ? array_values($batch['phrase_sprint']) : [];
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
