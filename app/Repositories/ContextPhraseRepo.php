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
        $this->contextPhrase        =   new ContextPhrase();
        $this->defineMeaningRepo    =   new DefineMeaningRepo();
        $this->voteExpiryRepo       =   new VoteExpiryRepo();
        $this->bidExpiryRepo        =   new BiddingExpiryRepo();
        $setting                    =   new SettingController();
        $this->mutualService        =   new MutualService();
        $this->total_context        =   $setting->getKeyValue(env('TOTAL_CONTEXT'))->values;
    }
    
    /**
     * @return mixed
     * get context Phrase List
     */
    public function getList(){
        return $this->contextPhrase->where('work_order', '!=', NULL)->leftJoin('context', 'context.context_id', '=','context_phrase.context_id')
            ->leftJoin('phrase', 'phrase.phrase_id', '=', 'context_phrase.phrase_id');
    }

    /**
     * @param $contexts
     * @return array
     * get paginated records
     */
    public function getPaginated(){
        return $this->getList()->limit($this->total_context)->orderBy('context_phrase.work_order', 'ASC')->get();
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     * get Context and meaning
     */
    public function getFirstPositionMeaning($data){
        $getContextPhrase=$this->getList()->where(['context_phrase.context_id'=>$data['context_id'], 'context_phrase.phrase_id'=>$data['phrase_id']])->first();
        $getMeaning=$this->defineMeaningRepo->fetchUserRecord($data);
        if(!empty($getMeaning)){
            if($getMeaning->user_id==Auth::user()->id && $getMeaning->coins!=NULL):
                $getContextPhrase->setAttribute('close_bid', 1);
            endif;
            $getContextPhrase->setAttribute('id', $getMeaning->id);
            $getContextPhrase->setAttribute('meaning', $getMeaning->meaning);
            $getContextPhrase->setAttribute('phrase_type', $getMeaning->phrase_type);
            $getContextPhrase->setAttribute('coins', $getMeaning->coins);
            $getContextPhrase->setAttribute('writer', $getMeaning->users->first_name.' '.$getMeaning->users->last_name);
        }
        return $getContextPhrase;
    }
}