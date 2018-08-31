<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 4:17 PM
 */

namespace App\Services;
use App\Repositories\CoinsRepo;
use App\Repositories\ContextPhraseRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\ProfileRepo;
use App\Repositories\FamiliarContextRepo;
use App\Repositories\ContextRepo;
use App\Repositories\RoleRepo;
use App\Repositories\UserRepo;
use Auth;

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
    public function __construct(DefineMeaningRepo $defineMeaningRepo, ContextPhraseRepo $contextPhrase, CoinsRepo $coinsRepo, ProfileRepo $profile, UserService $userService, UserRepo $userRepo, FamiliarContextRepo $familiarContext,ContextRepo $context, RoleRepo $role)
    {
        $this->contextRepo=$context;
        $this->roleRepo=$role;
        $this->familiarContext=$familiarContext;
        $this->userRepo=$userRepo;
        $this->userService=$userService;
        $this->profile=$profile;
        $this->coins=$coinsRepo;
        $this->contextPhrase=$contextPhrase;
        $this->defineMeaning=$defineMeaningRepo;
    }
    public function getParentContextList(){
        return $this->contextRepo->getLimitedRecords();
    }
    public function getPaginatedContent(){
        return $this->contextRepo->getPaginatedRecord();
    }
    public function getAllContextPhrase(){
        return $contextPhrase=$this->contextPhrase->getPaginated();
    }
    public function getContextPhrase($context_id, $phrase_id){
        return $contextPhrase=$this->contextPhrase->getContext($context_id, $phrase_id);
    }
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
       return $record=$this->defineMeaning->create($data);
    }
    /*
     * bidding on context meaning
     */
    public function bidding($data, $meaning_id){
        $this->defineMeaning->update($data, $meaning_id);
        $coins=Auth::user()->coins-$data['bid'];
        $userData=['coins'=>$coins];
        $this->userRepo->update(Auth::user()->id, $userData);
        return true;
    }
}