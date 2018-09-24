<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/29/18
 * Time: 12:19 PM
 */

namespace App\Repositories;


use App\ContextPhrase;
use App\Http\Controllers\SettingController;
use App\Services\MutualService;
use Auth;
use Carbon\Carbon;
use Config;

class ContextPhraseRepo
{
    protected $contextPhrase;
    protected $defineMeaningRepo;
    protected $voteExpiryRepo;
    protected $bidExpiryRepo;
    protected $setting;
    protected $total_context;
    protected $mutualService;

    public function __construct()
    {
        $contextPhrase= new ContextPhrase();
        $defineMeaningRepo=new DefineMeaningRepo();
        $voteExpiryRepo=new VoteExpiryRepo();
        $bidExpiry=new BiddingExpiryRepo();
        $this->contextPhrase=$contextPhrase;
        $this->defineMeaningRepo=$defineMeaningRepo;
        $this->voteExpiryRepo=$voteExpiryRepo;
        $this->bidExpiryRepo=$bidExpiry;
        $setting = new SettingController();
        $this->total_context=$setting->getKeyValue(env('TOTAL_CONTEXT'))->values;
        $this->mutualService=new MutualService();
    }

    /*
     * get context Phrase List
     */

    public function getList(){
        return $this->contextPhrase->where('work_order', '!=', NULL)->leftJoin('context', 'context.context_id', '=','context_phrase.context_id')
            ->leftJoin('phrase', 'phrase.phrase_id', '=', 'context_phrase.phrase_id');
    }
    /*
     * get paginated records
     */
    public function getPaginated($contexts){
        $contextPhrases=$this->getList()->whereIn('context_immediate_parent_id', $contexts)->orderBy('context_phrase.work_order', 'ASC')->get();
        $contributedMeaning=$this->defineMeaningRepo->getAllContributedMeaning();
        $totalContext=[];
        foreach($contextPhrases as $key=>$record):
            $checkVote=$this->voteExpiryRepo->checkRecords($record['context_id'], $record['phrase_id'], env('MEANING'));
            if(!empty($checkVote)):
                unset($contextPhrases[$key]);
            else:
                $totalContext[$key]['expiry_date']='';
                $checkBidExpiry=$this->bidExpiryRepo->checkPhraseExpiry($record['context_id'],$record['phrase_id'],  env('MEANING'));
                if(!empty($checkBidExpiry)):
                    $totalContext[$key]['expiry_date']=$this->mutualService->displayHumanTimeLeft($checkBidExpiry->expiry_date);
                endif;
                $totalContext[$key]['context_id']=$record['context_id'];
                $totalContext[$key]['phrase_id']=$record['phrase_id'];
                $totalContext[$key]['work_order']=$record['work_order'];
                $totalContext[$key]['context_name']=$record['context_name'];
                $totalContext[$key]['context_picture']=$record['context_picture'];
                $totalContext[$key]['phrase_text']=$record['phrase_text'];
                $totalContext[$key]['status']=Config::get('constant.phrase_status.open');
                foreach ($contributedMeaning as $meaning):
                    if($record['context_id']==$meaning['context_id'] && $record['phrase_id']==$meaning['phrase_id']):
                        if($meaning['coins']==NULL && $meaning['user_id']==Auth::user()->id):
                            $totalContext[$key]['status']=Config::get('constant.phrase_status.in-progress');
                        endif;
                        if($meaning['coins']!=NULL && $meaning['user_id']==Auth::user()->id):
                            $totalContext[$key]['status']=Config::get('constant.phrase_status.submitted');
                        endif;
                    endif;
                endforeach;
                $totalContext[$key]['clickable']='1';
            endif;
        endforeach;
        $totalContext = array_slice($totalContext, 0, $this->total_context);
        return $totalContext;
    }
    /*
     * get one context phrase
     */
    public function getContext($context_id, $phrase_id){
        $getContextPhrase=$this->getList()->where(['context_phrase.context_id'=>$context_id, 'context_phrase.phrase_id'=>$phrase_id])->first();
        $getMeaning=$this->defineMeaningRepo->fetchMeaning($context_id, $phrase_id);
        if(!empty($getMeaning)){
            if($getMeaning->user_id==Auth::user()->id && $getMeaning->coins!=NULL):
                $getContextPhrase->setAttribute('close_bid', 1);
            endif;
            $getContextPhrase->setAttribute('id', $getMeaning->id);
            $getContextPhrase->setAttribute('meaning', $getMeaning->meaning);
            $getContextPhrase->setAttribute('phrase_type', $getMeaning->phrase_type);
            $getContextPhrase->setAttribute('coins', $getMeaning->coins);
        }
        return $getContextPhrase;
    }
    /*
     * get Context and meaning
     */
    public function getFirstPositionMeaning($context_id, $phrase_id){
        $getContextPhrase=$this->getList()->where(['context_phrase.context_id'=>$context_id, 'context_phrase.phrase_id'=>$phrase_id])->first();
        $getMeaning=$this->defineMeaningRepo->selectedMeaning($context_id, $phrase_id);
        if(!empty($getMeaning)){
            $getContextPhrase->setAttribute('id', $getMeaning->id);
            $getContextPhrase->setAttribute('meaning', $getMeaning->meaning);
            $getContextPhrase->setAttribute('phrase_type', $getMeaning->phrase_type);
            $getContextPhrase->setAttribute('coins', $getMeaning->coins);
            $getContextPhrase->setAttribute('writer', $getMeaning->users->first_name.' '.$getMeaning->users->last_name);
        }
        return $getContextPhrase;
    }
}
