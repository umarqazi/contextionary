<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/4/18
 * Time: 3:02 PM
 */
namespace App\Services;

use App\Http\Controllers\SettingController;
use App\Repositories\ContextPhraseRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\IllustratorRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\UserPointRepo;
use App\Repositories\VoteExpiryRepo;
use App\Repositories\VoteMeaningRepo;
use Auth;
use Carbon\Carbon;
use Config;
use Mail;

Class VoteService{

    protected $voteExpiry;
    protected $voteMeaning;
    protected $defineMeaning;
    protected $illustrators;
    protected $contextPhrase;
    protected $userPoint;
    protected $voteExpiryDate;
    protected $mutual;
    protected $minimumVotes;
    protected $translations;
    protected $setting;

    /**
     * VoteService constructor.
     */
    public function __construct()
    {
        $this->voteExpiry       =   new VoteExpiryRepo();
        $this->defineMeaning    =   new DefineMeaningRepo();
        $this->contextPhrase    =   new ContextPhraseRepo();
        $this->voteMeaning      =   new VoteMeaningRepo();
        $this->userPoint        =   new UserPointRepo();
        $this->setting          =   new SettingController();
        $this->mutual           =   new MutualService();
        $this->illustrators     =   new IllustratorRepo();
        $this->translations     =   new TranslationRepo();
    }

    /**
     * @param $context
     * @param $phrase
     * @param $type
     * @return bool
     * update VoteExpiry
     */
    public function addPhraseForVote($context, $phrase, $type){
        $this->voteExpiryDate   =   $this->setting->getKeyValue(env('VOTE_EXPIRY'))->values;
        $date=Carbon::now()->addMinutes($this->voteExpiryDate);
        $data=['context_id'=>$context, 'phrase_id'=>$phrase, 'vote_type'=>$type];
        $record=$this->voteExpiry->checkRecords($data);
        if(!$record):
            $record['expiry_date']=$date;
            $this->voteExpiry->create($data);
        endif;
        return true;
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * get meanings for allVotes
     */
    public function getVoteList(){
        return $this->listForVoting(env('MEANING'),env('MEANING_KEY'), 'defineMeaning');
    }

    /**
     * @param $data
     * @return array|mixed
     * get meanings for vote
     */
    public function getVoteMeaning($data){
        $record=$this->checkActiveVotes($data, env('MEANING'));
        if($record){
            if($record->status=='0'){
                $data['user_id']=Auth::user()->id;
                $checkVote=$this->voteMeaning->checkUserVote($data, env('MEANING'));
                if(empty($checkVote)):
                    $checkMeaning=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id']];
                    $records=$this->contextPhrase->getFirstPositionMeaning($checkMeaning);
                    $records['allMeaning']=$this->defineMeaning->getAllVoteMeaning($data['context_id'], $data['phrase_id']);
                    return $records;
                else:
                    return $response=['voteStatus'=>0, 'message'=>trans('content.already_cast_vote')];
                endif;
            }else{
                return $response=['voteStatus'=>0, 'message'=>trans('content.expired_vote')];
            }
        }
        return $response=['voteStatus'=>0, 'message'=>trans('content.vote_not_available')];
    }

    /**
     * @param $data
     * @return array
     * add vote
     */
    public function vote($data){
        $this->voteMeaning->create($data);
        return ['status'=>true];
    }

    /**
     * @param $data
     * @return bool
     * poor quality vote
     */
    public function poorQualityVote($data){
        $record=$this->checkActiveVotes($data, $data['type']);
        if($record):
            if($record->status=='0'){
                $userVote=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'], 'user_id'=>Auth::user()->id];
                $checkVote=$this->voteMeaning->checkUserVote($userVote, $data['type']);
                if(empty($checkVote)):
                    $data['is_poor']='1';
                    $data['user_id']=Auth::user()->id;
                    return $this->voteMeaning->create($data);
                else:
                    return ['voteStatus'=>0, 'message'=>trans('content.already_cast_vote')];
                endif;
            }else{
                return ['voteStatus'=>0, 'message'=>trans('content.expired_vote')];
            }
        endif;
        return ['voteStatus'=>0, 'message'=>trans('content.vote_not_available')];
    }

    /**
     * @param $type
     * @param $column_key
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * get list of voting according to type
     */
    public function listForVoting($type, $column_key, $model){
        $this->minimumVotes     =   $this->setting->getKeyValue(env('MINIMUM_VOTES'))->values;
        $getContext=$this->mutual->getFamiliarContext(Auth::user()->id);
        $records=[];
        $contextPhrase=$getLatestVote=$this->voteExpiry->getAllVotes($type, $getContext);
        if($contextPhrase):
            foreach($contextPhrase as $key=>$context):
                $data=['context_id'=>$context->context_id, 'phrase_id'=>$context->phrase_id,'status'=>'1'];
                if($type==env('TRANSLATE')):
                    $data['language']=Auth::user()->profile->language_proficiency;
                    $checkLanguage=$this->$model->fetchUserRecord($data);
                    unset($data['language']);
                    if(empty($checkLanguage)):
                        continue;
                    endif;
                endif;
                $data['user_id']=Auth::user()->id;
                $checkUserPhrase=$this->$model->fetchUserRecord($data);
                unset($data['status']);
                if(empty($checkUserPhrase)):
                    $records[$key]['status']=Config::get('constant.vote_status.pending');
                    $records[$key]['clickable']='1';
                    $records[$key]['expiry_date']='';
                    $userVote=$this->voteMeaning->checkUserVote($data, $type);
                    if(!empty($userVote)):
                        $records[$key]['status']=Config::get('constant.vote_status.submitted');
                        $records[$key]['clickable']='0';
                    endif;
                    $checkMeaning=['context_id'=>$context->context_id, 'phrase_id'=>$context->phrase_id];
                    $contexts=$this->contextPhrase->getFirstPositionMeaning($checkMeaning);
                    $checkMeaning['type']=$type;
                    $getTotalVote=$this->voteMeaning->totalVotes($checkMeaning);
                    if($getTotalVote >= $this->minimumVotes):
                        $records[$key]['expiry_date']=$this->mutual->displayHumanTimeLeft($context['expiry_date']);
                    endif;
                    $records[$key]['context_id']=$contexts->context_id;
                    $records[$key]['phrase_id']=$contexts->phrase_id;
                    $records[$key]['work_order']=$contexts->work_order;
                    $records[$key]['context_name']=$contexts->context_name;
                    $records[$key]['context_picture']=$contexts->context_picture;
                    $records[$key]['phrase_text']=$contexts->phrase_text;
                endif;
            endforeach;
        endif;
        return $this->mutual->paginatedRecord($records, 'phrase-list');
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * illustrator list for vote
     */
    public function getIllustratorVoteList(){
        return $this->listForVoting(env('ILLUSTRATE'), env('ILLUSTRATOR_KEY'), 'illustrators');
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * translator list for vote
     */
    public function getTranslatorVoteList(){
        return $this->listForVoting(env('TRANSLATE'), env('TRANSLATOR_KEY'), 'translations');
    }
    /**
     * @param $data
     * @return array|mixed
     * get illustrators for vote
     */
    public function getVoteIllustrator($data){
        $record=$this->checkActiveVotes($data, env('ILLUSTRATE'));
        if($record){
            if($record->status=='0'){
                $checkVote=$this->voteMeaning->checkUserVote($data, env('ILLUSTRATE'));
                if(empty($checkVote)):
                    $checkMeaning=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id']];
                    $records=$this->contextPhrase->getFirstPositionMeaning($checkMeaning);
                    $records['illustrators']=$this->illustrators->getAllVoteIllustrators($data['context_id'], $data['phrase_id']);
                    return $records;
                else:
                    return ['voteStatus'=>0, 'message'=>trans('content.already_cast_vote')];
                endif;
            }else{
                return ['voteStatus'=>0, 'message'=>trans('content.expired_vote')];
            }
        }
        return ['voteStatus'=>0, 'message'=>trans('content.vote_not_available')];
    }

    /**
     * @param $data
     * @param $type
     * @return mixed
     */
    public function checkActiveVotes($data, $type){
        $checkActiveVote=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'], 'vote_type'=>$type];
        return $record=$this->voteExpiry->checkRecords($checkActiveVote);
    }
    /**
     * @param $data
     * @return array|mixed
     * get translators for vote
     */
    public function getVoteTranslators($data){
        $record=$this->checkActiveVotes($data, env('TRANSLATE'));
        if($record){
            if($record->status=='0'){
                $checkVote=$this->voteMeaning->checkUserVote($data, env('TRANSLATE'));
                if(empty($checkVote)):
                    $checkMeaning=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id']];
                    $records=$this->contextPhrase->getFirstPositionMeaning($checkMeaning);
                    $getIllustrator=['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'], 'position'=>'1'];
                    $illustraor_name=$this->illustrators->fetchUserRecord($getIllustrator);
                    if($illustraor_name):
                        $records['illustrators']=$illustraor_name->illustrator;
                    endif;
                    $records['translators']=$this->translations->getAllVoteTranslators($data['context_id'], $data['phrase_id']);
                    return $records;
                else:
                    return ['voteStatus'=>0, 'message'=>trans('content.already_cast_vote')];
                endif;
            }else{
                return ['voteStatus'=>0, 'message'=>trans('content.expired_vote')];
            }
        }
        return ['voteStatus'=>0, 'message'=>trans('content.vote_not_available')];
    }
}