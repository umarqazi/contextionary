<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 3:02 PM
 */
namespace App\Services;

use App\Repositories\ContextPhraseRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\VoteExpiryRepo;
use App\Repositories\VoteMeaningRepo;
use App\VoteMeaning;
use Carbon\Carbon;
Class VoteService{

    protected $voteExpiry;
    protected $voteMeaning;
    protected $defineMeaning;
    protected $contextPhrase;

    public function __construct()
    {
        $expiry=new VoteExpiryRepo();
        $define=new DefineMeaningRepo();
        $context=new ContextPhraseRepo();
        $vote=new VoteMeaningRepo();
        $this->voteExpiry=$expiry;
        $this->defineMeaning=$define;
        $this->contextPhrase=$context;
        $this->voteMeaning=$vote;
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
        return $this->voteMeaning->create($data);
    }
}