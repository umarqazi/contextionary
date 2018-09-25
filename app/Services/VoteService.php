<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 3:02 PM
 */
namespace App\Services;

use App\Http\Controllers\SettingController;
use App\Mail\Meanings;
use App\Repositories\ContextPhraseRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\UserPointRepo;
use App\Repositories\VoteExpiryRepo;
use App\Repositories\VoteMeaningRepo;
use App\VoteMeaning;
use Carbon\Carbon;
use Auth;
use Mail;
use Config;

Class VoteService{

    protected $voteExpiry;
    protected $voteMeaning;
    protected $defineMeaning;
    protected $contextPhrase;
    protected $userPoint;
    protected $voteExpiryDate;
    protected $mutual;

    public function __construct()
    {
        $expiry=new VoteExpiryRepo();
        $define=new DefineMeaningRepo();
        $context=new ContextPhraseRepo();
        $vote=new VoteMeaningRepo();
        $userPoint=new UserPointRepo();
        $setting = new SettingController();
        $mutualService=new MutualService();
        $this->mutual=$mutualService;
        $this->voteExpiryDate=$setting->getKeyValue(env('VOTE_EXPIRY'))->values;
        $this->voteExpiry=$expiry;
        $this->defineMeaning=$define;
        $this->contextPhrase=$context;
        $this->voteMeaning=$vote;
        $this->userPoint=$userPoint;
    }

    /**
     * update VoteExpiry
     */
    public function addPhraseForVote($context, $phrase, $type){
        $date=Carbon::now()->addMinutes($this->voteExpiryDate);
        $data=['context_id'=>$context, 'phrase_id'=>$phrase, 'vote_type'=>$type, 'expiry_date'=>$date];
        $record=$this->voteExpiry->checkRecords($context, $phrase, $type);
        if(!$record):
            $this->voteExpiry->create($data);
        endif;
        return true;
    }
    /**
     * get meanings for allVotes
     */
    public function getVoteList(){
        $getContext=$this->mutual->getFamiliarContext(Auth::user()->id);
        $records=[];
        $contextPhrase=$getLatestVote=$this->voteExpiry->getAllVotes(env('MEANING'), $getContext);
        if($contextPhrase):
            foreach($contextPhrase as $key=>$context):
                $records[$key]['status']=Config::get('constant.vote_status.pending');
                $records[$key]['clickable']='1';
                $data=['context_id'=>$context->context_id, 'phrase_id'=>$context->phrase_id];
                $userVote=$this->voteMeaning->checkUserVote($data);
                if(!empty($userVote)):
                    $records[$key]['status']=Config::get('constant.vote_status.submitted');
                    $records[$key]['clickable']='0';
                endif;
                $contexts=$this->contextPhrase->getContext($context->context_id, $context->phrase_id);
                $records[$key]['expiry_date']=$this->mutual->displayHumanTimeLeft($context['expiry_date']);
                $records[$key]['context_id']=$contexts->context_id;
                $records[$key]['phrase_id']=$contexts->phrase_id;
                $records[$key]['work_order']=$contexts->work_order;
                $records[$key]['context_name']=$contexts->context_name;
                $records[$key]['context_picture']=$contexts->context_picture;
                $records[$key]['phrase_text']=$contexts->hrase_text;
            endforeach;
        endif;
        return $this->mutual->paginatedRecord($records, 'phrase-list');
    }
    /**
     * get meanings for vote
     */
    public function getVoteMeaning($data){
        $checkVote=$this->voteMeaning->checkUserVote($data);
        if(empty($checkVote)):
            $records=$this->contextPhrase->getContext($data['context_id'], $data['phrase_id']);
            $records['allMeaning']=$this->defineMeaning->getAllVoteMeaning($data['context_id'], $data['phrase_id']);
            return $records;
        endif;
        return false;
    }
    /**
     * add vote
     */
    public function vote($data){
        $checkVote=$this->defineMeaning->getRecords($data['context_id'], $data['phrase_id'])->where('id', $data['define_meaning_id'])->first();
        if($checkVote->user_id==Auth::user()->id){
            return ['status'=>false, 'message'=>'You cannot vote on your meaning'];
        }
        $this->voteMeaning->create($data);
        return ['status'=>true];
    }
    /**
     * poor quality vote
     */
    public function poorQualityVote($data){
        $checkVote=$this->voteMeaning->checkUserVote($data);
        if(empty($checkVote)):
            $data['is_poor']='1';
            $data['user_id']=Auth::user()->id;
            return $this->voteMeaning->create($data);
        endif;
        return false;
    }
}