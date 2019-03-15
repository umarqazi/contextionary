<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 4:17 PM
 */

namespace App\Services;
use App\Http\Controllers\SettingController;
use App\Repositories\BiddingExpiryRepo;
use App\Repositories\CoinsRepo;
use App\Repositories\ContextPhraseRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\IllustratorRepo;
use App\Repositories\PhraseRepo;
use App\Repositories\ProfileRepo;
use App\Repositories\FamiliarContextRepo;
use App\Repositories\ContextRepo;
use App\Repositories\RoleRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\UserPointRepo;
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
    protected $total_context;
    protected $phraseRepo;
    protected $translationRepo;
    protected $setting;
    protected $min_bids;
    protected $userPoint;

    public function __construct()
    {
        $this->defineMeaning    =   new DefineMeaningRepo();
        $this->contextPhrase    =   new ContextPhraseRepo();
        $this->coins            =   new CoinsRepo();
        $this->profile          =   new ProfileRepo();
        $this->userService      =   new UserService();
        $this->userRepo         =   new UserRepo();
        $this->familiarContext  =   new FamiliarContextRepo();
        $this->roleRepo         =   new RoleRepo();
        $this->voteService      =   new VoteService();
        $this->biddingRepo      =   new BiddingExpiryRepo();
        $this->illustrate       =   new IllustratorRepo();
        $this->contextRepo      =   new ContextRepo();
        $this->mutualService    =   new MutualService();
        $this->voteExpiryRepo   =   new VoteExpiryRepo();
        $this->bidExpiryRepo    =   new BiddingExpiryRepo();
        $this->phraseRepo       =   new PhraseRepo();
        $this->translationRepo  =   new TranslationRepo();
        $this->setting          =   new SettingController();
        $this->userPoint        =   new UserPointRepo();

    }

    /**
     * @return mixed
     */
    public function getParentContextList(){
        return $this->contextRepo->getLimitedRecords();
    }
    /**
     * @return mixed
     * get Paginated Records
     */
    public function getPaginatedContent(){
        return $this->contextRepo->getPaginatedRecord();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllContextPhrase(){
        $contextPhrase=$this->contextPhrase->getPaginated();
        return $listOfPhrase=$this->bidPhraseList($contextPhrase, env('MEANING'), 'defineMeaning', 'define');
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * get context against specific id
     */
    public function getContextPhrase($context_id, $phrase_id){
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'user_id'=>Auth::user()->id];
        return $contextPhrase=$this->contextPhrase->getFirstPositionMeaning($data);

    }

    /**
     * @param $data
     * @return bool
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

    /**
     * @param $userId
     * @param $packageId
     * @return bool
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

    /**
     * @param $data
     * @return mixed
     * save meaning against context or phrase
     */
    public function saveContextMeaning($data){
        if($data['id']!=''):
            return $record=$this->defineMeaning->update($data, $data['id']);
        else:
            return $record=$this->defineMeaning->create($data);
        endif;
    }

    /**
     * @param $data
     * @param $meaning_id
     * @return bool
     * bidding on context meaning
     */
    public function bidding($data, $meaning_id){
        $coins=Auth::user()->coins-$data['coins'];
        if($coins < 0){
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
     * @return string
     * get Phrase Meanings
     */
    public function getVoteMeaning(){
        $records='';
        $getMeaning=$this->defineMeaning->voteMeaning();
        if($getMeaning){
            $data=['context_id'=>$getMeaning->context_id, 'phrase_id'=>$getMeaning->phrase_id, 'user_id'=>Auth::user()->id];
            $records=$this->contextPhrase->getFirstPositionMeaning($data);
            $records['allMeaning']=$this->defineMeaning->getAllVoteMeaning($getMeaning->context_id, $getMeaning->phrase_id);
        }
        return $records;
    }

    /**
     * @return array
     * get phrase for illustrate
     */
    public function getIllustratePhrase(){
        $listOfPhrase='';
        $contextPhrase=$this->defineMeaning->illustrates();
        if($contextPhrase):
            foreach($contextPhrase as $key=>$context):
                $record=$this->contextRepo->getContextName($context['context_id']);
                $phraseRecord=$this->phraseRepo->getPhraseName($context['phrase_id']);
                $contextPhrase[$key]['context_name'] = $record->context_name;
                $contextPhrase[$key]['context_picture'] = $record->context_picture;
                $contextPhrase[$key]['phrase_text'] = $phraseRecord->phrase_text;
                $contextPhrase[$key]['red_flag'] = $phraseRecord->red_flag;
            endforeach;
            $listOfPhrase=$this->bidPhraseList($contextPhrase, env('ILLUSTRATE'), 'illustrate', 'illustrate');
        endif;

        return $listOfPhrase;
    }

    /**
     * @param $data
     * @return mixed
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
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * get context against specific id
     */
    public function getMeaningForIllustrate($data){
        return $contextPhrase=$this->contextPhrase->getFirstPositionMeaning($data);
    }

    /**
     * make list of phrase
     */
    public function bidPhraseList($contextPhrase, $type, $repoName, $url){
        $this->total_context    =   $this->setting->getKeyValue(env('TOTAL_CONTEXT'))->values;
        $this->min_bids         =   $this->setting->getKeyValue(env('MINIMUM_BIDS'))->values;
        $this->contextArray     =   $this->mutualService->getFamiliarContext(Auth::user()->id);
        $totalContext=[];
        foreach($contextPhrase as $key=>$record):
            if(in_array($record['context_id'], $this->contextArray)){
                $checkActiveContext=['context_id'=>$record['context_id'], 'phrase_id'=>$record['phrase_id'], 'vote_type'=>$type];
                if($type==env('TRANSLATE')):
                    $checkActiveContext['language']=Auth::user()->profile->language_proficiency;
                endif;
                $checkVote=$this->voteExpiryRepo->checkRecords($checkActiveContext);
                if(!empty($checkVote)):
                    unset($contextPhrase[$key]);
                else:
                    $totalContext[$key]['expiry_date']='';
                    $checkMeaning=['context_id'=>$record['context_id'], 'phrase_id'=>$record['phrase_id']];
                    if($type==env('TRANSLATE')):
                        $checkMeaning['language']=Auth::user()->profile->language_proficiency;
                    endif;
                    $totalCount=$this->$repoName->totalRecords($checkMeaning);
                    if($totalCount >= $this->min_bids){
                        $checkBidExpiry=$this->bidExpiryRepo->checkPhraseExpiry($record['context_id'],$record['phrase_id'],  $type);
                        if(!empty($checkBidExpiry)):
                            $totalContext[$key]['expiry_date']=$this->mutualService->displayHumanTimeLeft($checkBidExpiry->expiry_date);
                        endif;
                    }
                    $totalContext[$key]['context_id']=$record['context_id'];
                    $totalContext[$key]['phrase_id']=$record['phrase_id'];
                    $totalContext[$key]['work_order']=$record['work_order'];
                    $totalContext[$key]['context_name']=$record['context_name'];
                    $totalContext[$key]['context_picture']=$record['context_picture'];
                    $totalContext[$key]['phrase_text']=$record['phrase_text'];
                    $totalContext[$key]['status']=Config::get('constant.phrase_status.open');
                    $checkMeaning['user_id']=Auth::user()->id;
                    $contributedMeaning=$this->$repoName->fetchUserRecord($checkMeaning);
                    if(!empty($contributedMeaning)):
                        if($contributedMeaning->coins==NULL):
                            $totalContext[$key]['status']=Config::get('constant.phrase_status.in-progress');
                        endif;
                        if($contributedMeaning->coins!=NULL):
                            $totalContext[$key]['status']=Config::get('constant.phrase_status.submitted');
                        endif;
                    endif;
                    $totalContext[$key]['clickable']='1';
                endif;
            }
        endforeach;
        $totalContext = array_slice($totalContext, 0, $this->total_context);
        return $pagination=$this->mutualService->paginatedRecord($totalContext, $url);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getIllustrator($data){
        return $this->illustrate->fetchUserRecord($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getIllustrations($data){
        return $this->illustrate->fetchRecord($data);
    }

    /**
     * @return array
     * get phrase for translator
     */
    public function getTranslateList(){
        $listOfPhrase='';
        $data=['status'=>'3', 'position'=>'1'];
        $contextPhrase=$this->illustrate->selectedIllustrates($data);
        if($contextPhrase):
            foreach($contextPhrase as $key=>$context):
                $record=$this->contextRepo->getContextName($context['context_id']);
                $phraseRecord=$this->phraseRepo->getPhraseName($context['phrase_id']);
                $contextPhrase[$key]['context_name'] = $record->context_name;
                $contextPhrase[$key]['context_picture'] = $record->context_picture;
                $contextPhrase[$key]['phrase_text'] = $phraseRecord->phrase_text;
                $contextPhrase[$key]['red_flag'] = $phraseRecord->red_flag;
            endforeach;
            $listOfPhrase=$this->bidPhraseList($contextPhrase, env('TRANSLATE'), 'translationRepo', 'translate');
        endif;

        return $listOfPhrase;
    }

    /**
     * @param $data
     * @return array
     */
    public function getSelectedIllustrators($data){
        $data['status']=3;
        $data['position']=1;
        $illustrator=[];
        $contextPhrase=$this->illustrate->fetchUserRecord($data);
        if($contextPhrase):
            $illustrator['illustrator']=$contextPhrase->illustrator;
            $illustrator['illustrator_writer']=$contextPhrase->users->first_name.' '.$contextPhrase->users->last_name;
        endif;
        return $illustrator;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function saveTranslation($data){
        if($data['id']!=NULL):
            return $this->translationRepo->update($data, $data['id']);
        else:
            return $this->translationRepo->create($data);
        endif;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getTranslation($data){
        return $this->translationRepo->fetchUserRecord($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getTranslations($data){
        return $this->translationRepo->fetchRecord($data);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function userHistory($user_id, $data=[]){
        $user_history=[];
        $translator=$illustrate=$define='';
        $user=$this->userRepo->findById($user_id);
        $define=$user->defineMeaning;
        $illustrate=$user->illustrator;
        $translator=$user->translation;
        if(!empty($data)):
            if($data['type']):
                if($data['type']=='writer'):
                    $illustrate='';
                    $translator='';
                elseif($data['type']=='illustrator'):
                    $define='';
                    $translator='';
                elseif($data['type']=='translator'):
                    $define='';
                    $illustrate='';
                endif;
            endif;
            if($data['status']!=''):
                if($define):
                    $define=$define->where('status',$data['status']);
                endif;
                if($illustrate):
                    $illustrate=$illustrate->where('status',$data['status']);
                endif;
                if($translator):
                    $translator=$translator->where('status',$data['status']);
                endif;
            endif;
            if($data['position']):
                if($define):
                    $define=$define->where('position',$data['position']);
                endif;
                if($illustrate):
                    $illustrate=$illustrate->where('position',$data['position']);
                endif;
                if($translator):
                    $translator=$translator->where('position',$data['position']);
                endif;
            endif;
        endif;
        $i=0;
        $familiar_contexts     =   $this->mutualService->getFamiliarContext($user_id);
        if($define):
            foreach($define as $writer):
                $user_point='-';
                $contribution_status=0;
                $context_name=$this->contextRepo->getContextName($writer['context_id']);
                $phrase_name=$this->phraseRepo->getPhraseName($writer['phrase_id']);
                $checkPoint=['context_id'=>$writer['context_id'], 'phrase_id'=>$writer['phrase_id'], 'user_id'=>$writer['user_id'], 'type'=>env('MEANING')];
                $point=$this->userPoint->getPoint($checkPoint);
                if($point):
                    $user_point=$point->point;
                endif;
                if(in_array($writer['context_id'], $familiar_contexts)):
                    $route=lang_route('defineMeaning', ['context_id'=>$writer['context_id'], 'phrase_id'=>$writer['phrase_id']]);
                else:
                    $route='';
                endif;
                if($writer['status']==0 && $writer['coins']==NULL){

                    $contribution_status=0;
                }elseif($writer['status']==0 && $writer['coins']!=NULL){

                    $contribution_status=1;
                }elseif($writer['status']==1){

                    $contribution_status=2;
                }elseif($writer['status']==2){

                    $contribution_status=3;
                }elseif($writer['status']==3 && $writer['position']==NULL){

                    $contribution_status=4;
                }elseif($writer['status']==3 && $writer['position']!=NULL){
                    if($writer['position']==1){

                        $contribution_status=5;
                    }elseif($writer['position']==2 || $writer['position']==3){
                        $contribution_status=6;
                    }
                }
                $user_history[$i]=['route'=>$route,'contribution'=>$writer['meaning'], 'type'=>'writer','date'=>$writer['created_at'],
                    'context_name'=>$context_name->context_name, 'phrase_name'=>$phrase_name->phrase_text,
                    'position'=>$writer['position'],'point'=>$user_point, 'coins'=>$writer['coins'], 'status'=>$contribution_status];
                $i++;
            endforeach;
        endif;
        if($illustrate):
            foreach($illustrate as $writer):
                $user_point='-';
                $contribution_status=0;
                $context_name=$this->contextRepo->getContextName($writer['context_id']);
                $phrase_name=$this->phraseRepo->getPhraseName($writer['phrase_id']);
                $checkPoint=['context_id'=>$writer['context_id'], 'phrase_id'=>$writer['phrase_id'], 'user_id'=>$writer['user_id'], 'type'=>env('ILLUSTRATE')];
                $point=$this->userPoint->getPoint($checkPoint);
                if($point):
                    $user_point=$point->point;
                endif;
                if(in_array($writer['context_id'], $familiar_contexts)):
                    $route=lang_route('addIllustrate', ['context_id'=>$writer['context_id'], 'phrase_id'=>$writer['phrase_id']]);
                else:
                    $route='';
                endif;
                if($writer['status']==0 && $writer['coins']==NULL){

                    $contribution_status=0;
                }elseif($writer['status']==0 && $writer['coins']!=NULL){

                    $contribution_status=1;
                }elseif($writer['status']==1){

                    $contribution_status=2;
                }elseif($writer['status']==2){

                    $contribution_status=3;
                }elseif($writer['status']==3 && $writer['position']==NULL){

                    $contribution_status=4;
                }elseif($writer['status']==3 && $writer['position']!=NULL){

                    if($writer['position']==1){

                        $contribution_status=5;
                    }elseif($writer['position']==2 || $writer['position']==3){
                        $contribution_status=6;
                    }
                }
                $user_history[$i]=['route'=>$route,'contribution'=>$writer['illustrator'], 'type'=>'illustrator','date'=>$writer['created_at'],
                    'context_name'=>$context_name->context_name, 'phrase_name'=>$phrase_name->phrase_text,
                    'position'=>$writer['position'],'point'=>$user_point,  'coins'=>$writer['coins'], 'status'=>$contribution_status];
                $i++;
            endforeach;
        endif;
        if($translator):
            foreach($translator as $writer):
                $user_point='-';
                $contribution_status=0;
                $context_name=$this->contextRepo->getContextName($writer['context_id']);
                $phrase_name=$this->phraseRepo->getPhraseName($writer['phrase_id']);
                $checkPoint=['context_id'=>$writer['context_id'], 'phrase_id'=>$writer['phrase_id'], 'user_id'=>$writer['user_id'], 'type'=>env('TRANSLATE')];
                $point=$this->userPoint->getPoint($checkPoint);
                if($point):
                    $user_point=$point->point;
                endif;
                if(in_array($writer['context_id'], $familiar_contexts)):
                    $route=lang_route('addTranslate', ['context_id'=>$writer['context_id'], 'phrase_id'=>$writer['phrase_id']]);
                else:
                    $route='';
                endif;
                if($writer['status']==0 && $writer['coins']==NULL){

                    $contribution_status=0;
                }elseif($writer['status']==0 && $writer['coins']!=NULL){

                    $contribution_status=1;
                }elseif($writer['status']==1){

                    $contribution_status=2;
                }elseif($writer['status']==2){

                    $contribution_status=3;
                }elseif($writer['status']==3 && $writer['position']==NULL){

                    $contribution_status=4;
                }elseif($writer['status']==3 && $writer['position']!=NULL){

                    if($writer['position']==1){

                        $contribution_status=5;
                    }elseif($writer['position']==2 || $writer['position']==3){
                        $contribution_status=6;
                    }
                }
                $user_history[$i]=['route'=>$route,'contribution'=>$writer['translation'], 'type'=>'translator','language'=>$writer['language'],'date'=>$writer['created_at'],
                    'context_name'=>$context_name->context_name, 'phrase_name'=>$phrase_name->phrase_text,
                    'position'=>$writer['position'],'point'=>$user_point, 'coins'=>$writer['coins'], 'status'=>$contribution_status];
                $i++;
            endforeach;
        endif;
        return $records=$this->mutualService->paginatedRecord($user_history, 'user-history');
    }

    /**
     * @return mixed
     */
    public function countContributions(){
        return $this->defineMeaning->getUserContributions(Auth::user()->id) + $this->illustrate->getUserContributions(Auth::user()->id) + $this->translationRepo->getUserContributions(Auth::user()->id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getIllustration($data){
        return $this->illustrate->fetchRecord($data);
    }
}
