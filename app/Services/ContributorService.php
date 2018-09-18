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
    protected $contextArray=array();

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
        $contextRepo=new ContextRepo();
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
     * @return mixed
     */
    public function getFamiliarContext($user_id){
        return $getFamiliarContext=$this->familiarContext->getContext($user_id);
    }
    public function getAllContextPhrase(){
        $getFamiliarContext=$this->getFamiliarContext(Auth::user()->id);
        $getAllContext=$this->contextRepo->getContext();
        $contexts=[];
        foreach ($getFamiliarContext as $key=> $context):
            array_push($this->contextArray, $context['context_id']);
            $contexts[$key]['child']=$this->contextChildList($getAllContext, $context['context_id']);
        endforeach;
        $this->contextArray=array_unique($this->contextArray);
        return $contextPhrase=$this->contextPhrase->getPaginated($this->contextArray);
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
        $contextPhrase=$this->defineMeaning->illustrates();
        foreach($contextPhrase as $key=>$phrase):
            $contextPhrase[$key]['status']=Config::get('constant.phrase_status.open');
            $contextDetail=$this->contextPhrase->getContext($phrase['context_id'], $phrase['phrase_id']);
            $checkUserIllustrator=$this->getIllustrator($phrase['context_id'], $phrase['phrase_id']);
            if($checkUserIllustrator):
                if($checkUserIllustrator->coins!=NULL):
                    $contextPhrase[$key]['status']=Config::get('constant.phrase_status.submitted');
                else:
                    $contextPhrase[$key]['status']=Config::get('constant.phrase_status.in-progress');
                endif;

            endif;
            $contextPhrase[$key]['context_name']=$contextDetail->context_name;
            $contextPhrase[$key]['context_picture']=$contextDetail->context_picture;
            $contextPhrase[$key]['phrase_text']=$contextDetail->phrase_text;
        endforeach;
        return $contextPhrase;
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
    public function getIllustrator($context_id, $phrase_id){
        return $this->illustrate->currentUserIllustrate($context_id, $phrase_id, Auth::user()->id);
    }
    /*
     * get context against specific id
     */
    public function getMeaningForIllustrate($context_id, $phrase_id){
        return $contextPhrase=$this->contextPhrase->getFirstPositionMeaning($context_id, $phrase_id);
    }
    /**
     * get context and child of all context
     */
    public function contextChildList($array, $parentId = 0){
        $branch = array();
        foreach ($array as $key=>$element) {
            if ($element['context_immediate_parent_id'] == $parentId) {
                array_push($this->contextArray, $element['context_id']);
                $children = $this->contextChildList($array, $element['context_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$key] = $element;
            }

        }
        return $branch;
    }
}
