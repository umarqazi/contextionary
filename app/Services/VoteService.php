<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 3:02 PM
 */
namespace App\Services;

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

    public function __construct()
    {
        $expiry=new VoteExpiryRepo();
        $define=new DefineMeaningRepo();
        $context=new ContextPhraseRepo();
        $vote=new VoteMeaningRepo();
        $userPoint=new UserPointRepo();
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
        $date=Carbon::now()->addMonths(1);
        $data=['context_id'=>$context, 'phrase_id'=>$phrase, 'vote_type'=>$type, 'expiry_date'=>$date];
        $record=$this->voteExpiry->checkRecords($context, $phrase);
        if(!$record):
            $this->voteExpiry->create($data);
        endif;
        return true;
    }
    /**
     * get meanings for allVotes
     */
    public function getVoteList(){
        $records=[];
        $contextPhrase=$getLatestVote=$this->voteExpiry->getAllVotes('meaning');
        if($contextPhrase):
            foreach($contextPhrase as $key=>$context):
                $contextPhrase[$key]['status']=Config::get('constant.vote_status.pending');
                $data=['context_id'=>$context->context_id, 'phrase_id'=>$context->phrase_id];
                $userVote=$this->voteMeaning->checkUserVote($data);
                if(!empty($userVote)):
                    $contextPhrase[$key]['status']=Config::get('constant.vote_status.submitted');
                endif;
                $contextPhrase[$key]['context_info']=$this->contextPhrase->getContext($context->context_id, $context->phrase_id);
            endforeach;
            return $contextPhrase;
        endif;

        return $records;
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
    /**
     * check expired votes
     */
    public function checkExpiredVotes(){
        $getVote=$this->voteExpiry->getAllMeaningVotes('meaning');
        if($getVote):
            foreach($getVote as $vote):
                $cron_run='0';
                $getTotalVote=$this->voteMeaning->totalVotes($vote['context_id'], $vote['phrase_id']);
                if($getTotalVote < 5):
                    if($vote['expiry_date'] < Carbon::today()):
                        $cron_run='1';
                    endif;
                else:
                    $cron_run='1';
                endif;
                if($cron_run=='1'):
                    $getHighestVotes=$this->voteMeaning->hightVotes($vote['context_id'], $vote['phrase_id']);
                    if(!empty($getHighestVotes)):
                        $this->defineMeaning->updateVoteStatus($vote['context_id'], $vote['phrase_id']);
                        foreach($getHighestVotes as $key=>$hightesVotes):
                            if($key+1=='1'):
                                $points=10;
                            else:
                                $points=1;
                            endif;
                            $data=['point'=>$points,'context_id'=>$vote['context_id'], 'phrase_id'=>$vote['phrase_id'], 'user_id'=>$hightesVotes['meaning']['user_id'], 'position'=>$key+1];
                            $this->userPoint->create($data);
                            $hightesVotes['points']=$points;
                            if($key+1==1):
                                $position='1st';
                            elseif($key+1==2):
                                $position='2nd';
                            else:
                                $position='3rd';
                            endif;
                            $hightesVotes['position']=$position;
                            Mail::to($hightesVotes['meaning']['users']['email'])->send(new Meanings($hightesVotes));
                            $this->defineMeaning->update(['position'=>$key+1], $hightesVotes['meaning']['id']);
                        endforeach;
                        $this->voteExpiry->updateStatus($vote['id']);
                    endif;
                endif;
            endforeach;
        endif;
    }
}