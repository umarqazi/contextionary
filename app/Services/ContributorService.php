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
    protected $mutualService;
    protected $contextArray=array();
    protected $user_id;
    protected $voteExpiryRepo;
    protected $bidExpiryRepo;

    public function __construct()
    {
        $defineMeaningRepo=new DefineMeaningRepo();
        $contextPhrase=new ContextPhraseRepo();
        $coinsRepo=new CoinsRepo();
        $profile=new ProfileRepo();
        $userService=new UserService();
        $userRepo=new UserRepo();
        $familiarContext=new FamiliarContextRepo();
        $role=new RoleRepo();
        $voteService=new VoteService();
        $biddingRepo=new BiddingExpiryRepo();
        $illustrateRepo=new IllustratorRepo();
        $contextRepo=new ContextRepo();
        $mutualService=new MutualService();
        $this->voteExpiryRepo=new VoteExpiryRepo();
        $this->bidExpiryRepo=new BiddingExpiryRepo();
        $this->mutualService=$mutualService;
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
        $this->contextRepo=$contextRepo;
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

    /**
     * @return LengthAwarePaginator
     */
    public function getAllContextPhrase(){
        $this->contextArray=$this->mutualService->getFamiliarContext(Auth::user()->id);
        $contextPhrase=$this->contextPhrase->getPaginated($this->contextArray);
        return $pagination=$this->mutualService->paginatedRecord($contextPhrase, 'define');
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
        $this->familiarContext->delete($data['user_id']);
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
            $this->biddingRepo->addBidExpiry($data, $data['type']);
        }
        $coins=Auth::user()->coins-$data['coins'];
        $userData=['coins'=>$coins];
        $this->userRepo->update(Auth::user()->id, $userData);
        return true;
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
        $this->contextArray=$this->mutualService->getFamiliarContext(Auth::user()->id);
        $contextPhrase=$this->defineMeaning->illustrates($this->contextArray);
        $illustrators=[];
        $user_id=Auth::user()->id;
        foreach($contextPhrase as $key=>$phrase):
            $checkVote=$this->voteExpiryRepo->checkRecords($phrase['context_id'], $phrase['phrase_id'], env('ILLUSTRATE'));
            if(!empty($checkVote)):
                unset($contextPhrase[$key]);
            else:
                $illustrators[$key]['clickable']='1';
                $illustrators[$key]['expiry_date']='';
                $illustrators[$key]['status']=Config::get('constant.phrase_status.open');
                $contextDetail=$this->contextPhrase->getContext($phrase['context_id'], $phrase['phrase_id']);
                $checkIllustrate=['context_id'=>$phrase['context_id'], 'phrase_id'=>$phrase['phrase_id'], 'user_id'=>$user_id];
                $checkUserIllustrator=$this->getIllustrator($checkIllustrate);
                if($checkUserIllustrator):
                    if($checkUserIllustrator->coins!=NULL):
                        $illustrators[$key]['status']=Config::get('constant.phrase_status.submitted');
                    else:
                        $illustrators[$key]['status']=Config::get('constant.phrase_status.in-progress');
                    endif;
                endif;
                $checkBidExpiry=$this->bidExpiryRepo->checkPhraseExpiry($phrase['context_id'], $phrase['phrase_id'], env('ILLUSTRATE'));
                if(!empty($checkBidExpiry)):
                    $illustrators[$key]['expiry_date']=$this->mutualService->displayHumanTimeLeft($checkBidExpiry->expiry_date);
                endif;
                $illustrators[$key]['context_id']=$phrase['context_id'];
                $illustrators[$key]['phrase_id']=$phrase['phrase_id'];
                $illustrators[$key]['context_name']=$contextDetail->context_name;
                $illustrators[$key]['context_picture']=$contextDetail->context_picture;
                $illustrators[$key]['phrase_text']=$contextDetail->phrase_text;
            endif;
        endforeach;
        $illustrators=$this->mutualService->paginatedRecord($illustrators, 'illustrator');

        return $illustrators;
    }
    /**
     * save illustrator
     */
    public function saveIllustrate($data){
        if($data['id']!=NULL):
            return $this->illustrate->update($data, $data['id']);
        else:
            return $this->illustrate->create($data);
        endif;

    }
    /**
     * get Illustrator
     */
    public function getIllustrator($data){
        return $this->illustrate->currentUserIllustrate($data);
    }
    /*
     * get context against specific id
     */
    public function getMeaningForIllustrate($context_id, $phrase_id){
        return $contextPhrase=$this->contextPhrase->getFirstPositionMeaning($context_id, $phrase_id);
    }
}