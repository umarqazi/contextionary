<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/29/18
 * Time: 12:19 PM
 */

namespace App\Repositories;


use App\ContextPhrase;
use App\Phrase;
use App\Repositories\DefineMeaningRepo;
use App\Http\Controllers\SettingController;
use App\Services\MutualService;
use Auth;
use Carbon\Carbon;
use Config;

class ContextPhraseRepo
{
    /**
     * @var ContextPhrase
     */
    protected $contextPhrase;

    /**
     * @var \App\Repositories\DefineMeaningRepo
     */
    protected $defineMeaningRepo;

    /**
     * @var VoteExpiryRepo
     */
    protected $voteExpiryRepo;

    /**
     * @var Phrase
     */
    protected $phrase;
    protected $bidExpiryRepo;
    protected $setting;
    protected $total_context;
    protected $mutualService;

    /**
     * ContextPhraseRepo constructor.
     */
    public function __construct()
    {
        $this->contextPhrase        =   new ContextPhrase();
        $this->defineMeaningRepo    =   new DefineMeaningRepo();
        $this->voteExpiryRepo       =   new VoteExpiryRepo();
        $this->bidExpiryRepo        =   new BiddingExpiryRepo();
        $this->setting              =   new SettingController();
        $this->mutualService        =   new MutualService();
        $this->phrase               =   new Phrase();
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
        $this->total_context = $this->setting->getKeyValue(env('TOTAL_CONTEXT'))->values;
        return $context=$this->getList()->where('status', '=', NULL)->limit($this->total_context)->orderBy('context_phrase.work_order', 'ASC')->get();
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

    /**
     * @return mixed
     */
    public function getRandContextPhrase($context_id)
    {
        return $this->contextPhrase->getRand($context_id);
    }

    /**
     * @return mixed
     */
    public function getContextPhrase($context){
        return $this->contextPhrase->getContextPhrase($context);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPhrase($id){
        return $this->phrase->get($id);
    }

    /**
     * @param $length
     * @return mixed
     */
    public function getLengthed($length){
        return $this->phrase->getLengthed($length);
    }

    /**
     * @param $check
     * @param $data
     * @return mixed
     */
    public function updateStatus($check, $data){
        return $this->contextPhrase->where($check)->update($data);
    }
}
