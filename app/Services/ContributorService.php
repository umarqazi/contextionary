<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 4:17 PM
 */

namespace App\Services;
use App\Repositories\BiddingExpiryRepo;
use App\Repositories\CoinsRepo;
use App\Repositories\ContextPhraseRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\ProfileRepo;
use App\Repositories\FamiliarContextRepo;
use App\Repositories\ContextRepo;
use App\Repositories\RoleRepo;
use App\Repositories\UserRepo;
use App\Repositories\VoteExpiryRepo;
use Auth;
use Carbon\Carbon;

class ContributorService implements IService
{
    protected $contextRepo;
    protected $roleRepo;
    protected $familiarContext;
    protected $userRepo;
    protected $userService;
    protected $profile;
    protected $coins;
    protected $contextPhrase;
    protected $defineMeaning;
    protected $voteService;
    protected $biddingRepo;

    public function __construct()
    {
        $defineMeaningRepo=new DefineMeaningRepo();
        $contextPhrase=new ContextPhraseRepo();
        $coinsRepo=new CoinsRepo();
        $profile=new ProfileRepo();
        $userService=new UserService();
        $userRepo=new UserRepo();
        $familiarContext=new FamiliarContextRepo();
        $context=new ContextRepo();
        $role=new RoleRepo();
        $voteService=new VoteService();
        $biddingRepo=new BiddingExpiryRepo();
        $this->contextRepo=$context;
        $this->roleRepo=$role;
        $this->familiarContext=$familiarContext;
        $this->userRepo=$userRepo;
        $this->userService=$userService;
        $this->profile=$profile;
        $this->coins=$coinsRepo;
        $this->contextPhrase=$contextPhrase;
        $this->defineMeaning=$defineMeaningRepo;
        $this->voteService=$voteService;
        $this->biddingRepo=$biddingRepo;
    }

    public function getParentContextList(){
        return $this->contextRepo->getLimitedRecords();
    }
    /*
     * get Paginated Records
     */
    public function getPaginatedContent(){
        return $this->contextRepo->getPaginatedRecord();
    }

    public function getAllContextPhrase(){
        return $contextPhrase=$this->contextPhrase->getPaginated();
    }
    /*
     * get context against specific id
     */
    public function getContextPhrase($context_id, $phrase_id){
        return $contextPhrase=$this->contextPhrase->getContext($context_id, $phrase_id);
    }
    /*
     * update contributor records
     */
    public function updateContributorRecord($data){
        $assignRole=$this->roleRepo->assignMultiRole($data['user_id'], $data['role']);
        $familiarContext=[];
        foreach($data['context'] as $key=>$context){
            $familiarContext[$key]=['user_id'=>$data['user_id'], 'context_id'=>$context];
        }
        $this->familiarContext->create($familiarContext);
        $this->profile->update($data['user_id'], ['language_proficiency'=>$data['language']]);
        $this->userService->verificationEmail($data['user_id']);
        return true;
    }
    /*
     * update coins after purchase
     */
    public function updateCoins($userId, $packageId){
        $coins='';
        $getUser=$this->userRepo->findById($userId);
        $getCoin=$this->coins->findById($packageId);
        if($getUser):
            $coins=['coins'=>$getUser->coins+$getCoin->coins];
            $updateUser=$this->userRepo->update($userId, $coins);
        endif;
        return true;
    }
    /*
     * save meaning against context or phrase
     */
    public function saveContextMeaning($data){
        if($data['id']!=''):
            return $record=$this->defineMeaning->update($data, $data['id']);
        else:
            return $record=$this->defineMeaning->create($data);
        endif;
    }
    /*
     * bidding on context meaning
     */
    public function bidding($data, $meaning_id){
        $coins=Auth::user()->coins-$data['coins'];
        if($coins <= 0){
            return false;
        }
        $this->defineMeaning->update($data, $meaning_id);
        $this->defineMeaning->addExpiry($meaning_id);
        $coins=Auth::user()->coins-$data['coins'];
        $userData=['coins'=>$coins];
        $this->userRepo->update(Auth::user()->id, $userData);
        return true;
    }
    /*
     * cron job for checking availability of meaning for vote
     */
    public function checkMeaning(){
        $today=Carbon::today();
        $getAllMeaning=$this->defineMeaning->fetchContextPhraseMeaning();
        foreach($getAllMeaning as $meaning):
            if($meaning['context_id']!=NULL && $meaning['phrase_id']!=NULL):
                if($meaning['total'] < 2):
                    if(Carbon::parse($meaning['expiry_date']) < Carbon::parse($today)):
                        $updateMeaningStatus=$this->defineMeaning->updateMeaningStatus($meaning['context_id'], $meaning['phrase_id']);
                        $this->voteService->addPhraseForVote($meaning['context_id'], $meaning['phrase_id'], 'meaning');
                    endif;
                else:
                    $updateMeaningStatus=$this->defineMeaning->updateMeaningStatus($meaning['context_id'], $meaning['phrase_id']);
                    $this->voteService->addPhraseForVote($meaning['context_id'], $meaning['phrase_id'], 'meaning');
                endif;
            endif;
        endforeach;
    }
    /**
     * get Phrase Meanings
     */
    public function getVoteMeaning(){
        $records='';
        $getMeaning=$this->defineMeaning->voteMeaning();
        if($getMeaning){
            $records=$this->contextPhrase->getContext($getMeaning->context_id, $getMeaning->phrase_id);
            $records['allMeaning']=$this->defineMeaning->getAllVoteMeaning($getMeaning->context_id, $getMeaning->phrase_id);
        }
        return $records;
    }
}