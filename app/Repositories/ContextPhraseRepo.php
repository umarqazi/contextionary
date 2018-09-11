<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/29/18
 * Time: 12:19 PM
 */

namespace App\Repositories;


use App\ContextPhrase;
use App\Repositories\DefineMeaningRepo;

class ContextPhraseRepo
{
    protected $contextPhrase;
    protected $defineMeaningRepo;

    public function __construct()
    {
        $contextPhrase= new ContextPhrase();
        $defineMeaningRepo=new DefineMeaningRepo();
        $this->contextPhrase=$contextPhrase;
        $this->defineMeaningRepo=$defineMeaningRepo;
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
    public function getPaginated(){
        $contextPhrases=$this->getList()->orderBy('context_phrase.work_order', 'ASC')->paginate(9);
        $contributedMeaning=$this->defineMeaningRepo->getAllContributedMeaning();
        foreach($contextPhrases as $key=>$record):
            $contextPhrases[$key]['status']='';
            foreach ($contributedMeaning as $meaning):
                if($record['context_id']==$meaning['context_id'] && $record['phrase_id']==$meaning['phrase_id']):
                    $contextPhrases[$key]['status']='disabled';
                endif;
            endforeach;
        endforeach;
        return $contextPhrases;
    }
    /*
     * get one context phrase
     */
    public function getContext($context_id, $phrase_id){
        $getContextPhrase=$this->getList()->where(['context_phrase.context_id'=>$context_id, 'context_phrase.phrase_id'=>$phrase_id])->first();
        $getMeaning=$this->defineMeaningRepo->fetchMeaning($context_id, $phrase_id);
        if(!empty($getMeaning)){
            $getContextPhrase->setAttribute('id', $getMeaning->id);
            $getContextPhrase->setAttribute('meaning', $getMeaning->meaning);
            $getContextPhrase->setAttribute('phrase_type', $getMeaning->phrase_type);
        }
        return $getContextPhrase;
    }
}