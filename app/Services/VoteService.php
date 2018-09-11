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
     * get meanings for vote
     */
    public function getVoteMeaning(){
        $records='';
        $contextPhrase=$getLatestVote=$this->voteExpiry->getLatest('meaning');
        if($contextPhrase):
            $records=$this->contextPhrase->getContext($contextPhrase->context_id, $contextPhrase->phrase_id);
            $records['allMeaning']=$this->defineMeaning->getAllVoteMeaning($contextPhrase->context_id, $contextPhrase->phrase_id);
            return $records;
        endif;
        return $records;
    }
    /**
     * add vote
     */
    public function vote($data){
        return $this->voteMeaning->create($data);
    }
}