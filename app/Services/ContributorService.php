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
use App\Repositories\IllustratorRepo;
use App\Repositories\ProfileRepo;
use App\Repositories\FamiliarContextRepo;
use App\Repositories\ContextRepo;
use App\Repositories\RoleRepo;
use App\Repositories\UserRepo;
use App\Repositories\VoteExpiryRepo;
use Auth;
use Carbon\Carbon;
use Config;

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
    protected $illustrate;

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
        $illustrateRepo=new IllustratorRepo();
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
        $this->illustrate=$illustrateRepo;
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
        $repository=$data['model'];
        $updateColumns=['coins'=>$data['coins']];
        $this->$repository->update($updateColumns, $meaning_id);
        $total=$this->$repository->checkTotalPhrase($meaning_id, $data['type']);
        if($total=='1'){
            $this->defineMeaning->addBidExpiry($data, $data['type']);
        }
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
        $getAllMeaning=$this->defineMeaning->fetchBidding('meaning');
        if($getAllMeaning){
            foreach($getAllMeaning as $meaning):
                $context_id=$meaning->context_id;
                $phrase_id=$meaning->phrase_id;
                $expiry_date=$meaning->expiry_date;
                if($context_id!=NULL && $phrase_id!=NULL):
                    $cron_job='0';
                    $checkTotal=$this->defineMeaning->totalMeaning($context_id, $phrase_id);
                    if($checkTotal < 5):
                        if(Carbon::parse($expiry_date) < Carbon::parse($today)):
                            $cron_job='1';
                        endif;
                    else:
                        $cron_job='1';
                    endif;
                    if($cron_job=='1'):
                        $updateMeaningStatus=$this->defineMeaning->updateMeaningStatus($context_id, $phrase_id);
                        $this->voteService->addPhraseForVote($context_id, $phrase_id, 'meaning');
                        $updateBidding=$this->defineMeaning->updateBiddingStatus($meaning->id);
                    endif;
                endif;
            endforeach;
        }

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
    /**
     * get phrase for illustrate
     */
    public function getIllustratePhrase(){
        $contextPhrase=$this->defineMeaning->illustrates();
        foreach($contextPhrase as $key=>$phrase):
            $contextPhrase[$key]['status']=Config::get('constant.phrase_status.open');
            $contextDetail=$this->contextPhrase->getContext($phrase['context_id'], $phrase['phrase_id']);
            $checkUserIllustrator=$this->getIllustrator($phrase['context_id'], $phrase['phrase_id']);
            if(!empty($checkUserIllustrator)):
                $contextPhrase[$key]['status']=Config::get('constant.phrase_status.in-progress');
            endif;
            $contextPhrase[$key]['context']=$contextDetail;
        endforeach;
        return $contextPhrase;
    }
    /**
     * save illustrator
     */
    public function saveIllustrate($data){
        return $this->illustrate->create($data);
    }
    /**
     * get Illustrator
     */
    public function getIllustrator($context_id, $phrase_id){
        return $this->illustrate->currentUserIllustrate($context_id, $phrase_id, Auth::user()->id);
    }
}