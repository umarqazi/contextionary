<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:06
 */

namespace App\Http\Controllers;

use App\Services\ContextPhraseService;
use App\Services\ContextService;
use App\Services\ContributorService;
use App\Services\MeaningService;
use App\Services\MutualService;
use App\Services\PhraseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LearningCenterController extends Controller
{
    protected $context_service;
    protected $phrase_service;
    protected $context_phrase_service;
    protected $meaning_service;
    protected $contributor_service;
    protected $mutualService;

    public function __construct()
    {
        $context_service                =   new ContextService();
        $phrase_service                 =   new PhraseService();
        $context_phrase_service         =   new ContextPhraseService();
        $meaning_service                =   new MeaningService();
        $contributor_service            =   new ContributorService();
        $this->context_service          =   $context_service;
        $this->phrase_service           =   $phrase_service;
        $this->context_phrase_service   =   $context_phrase_service;
        $this->meaning_service          =   $meaning_service;
        $this->contributor_service      =   $contributor_service;
        $this->mutualService            =  new MutualService();
    }

    /**
     * @return mixed
     */
    public function index(){
        return View::make('guest_pages.learning_center');
    }

    /**
     * @param null $search
     * @return mixed
     */
    public function exploreContext($search = null){
        $context_array=[];
        $contexts = $this->context_phrase_service->listing($search);
        foreach($contexts as $key=>$context) {
            $context_array[$context['contexts']['context_name']]['context_id'] = $context['context_id'];
            $context_array[$context['contexts']['context_name']]['context_name'] = $context['contexts']['context_name'];
        }
        ksort($context_array);
        $contexts=$this->mutualService->paginatedRecord($context_array, 'explore-context', 100);
        return View::make('user.user_plan.learning_center.explore_context')->with('contexts', $contexts);
    }

    /**
     * @return mixed
     */
    public function detailContext(){
        return View::make('user.user_plan.learning_center.detail_context');
    }

    /**
     * @return mixed
     */
    public function exploreWord(){
        return View::make('user.user_plan.learning_center.explore_word');
    }

    /**
     * @param $context_id
     * @return mixed
     */
    public function exploreContextPhrase($context_id){
        $context_array=[];
        $context    = $this->context_service->findById($context_id);
        $phrases    = $this->context_phrase_service->getContextPhrase($context_id);
        foreach($phrases as $key=>$phrase) {
            if($phrase['phrases']!=NULL){
                $context_array[$phrase['phrases']['phrase_text']]['phrase_id'] = $phrase['phrases']['phrase_id'];
                $context_array[$phrase['phrases']['phrase_text']]['phrase_text'] = $phrase['phrases']['phrase_text'];
            }
        }
        ksort($context_array);
        $phrases=$this->mutualService->paginatedRecord($context_array, 'learning-center/explore-context/'.$context_id, 100);
        return View::make('user.user_plan.learning_center.explore_context2')->with(['phrases'=> $phrases, 'context' =>                  $context->context_name, 'context_id' => $context->context_id, 'type' => 'context_forwarded']);
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     */
    public function phraseDetail($context_id, $phrase_id){
        $context            = $this->context_service->findById($context_id);
        $phrase             = $this->phrase_service->findById($phrase_id);
        $data = [
            'context_id'    => $context->context_id,
            'phrase_id'     => $phrase->phrase_id,
            'position'      => 1,
        ];
        $meaning            = $this->meaning_service->meaningData($data);
        $related_phrases    = $this->context_phrase_service->getRelatedPhrase($context_id, $phrase_id);
        $translations       = $this->contributor_service->getTranslations($data);
        $illustration       = $this->contributor_service->getIllustration($data);
        if($illustration != null){
            $illustration = $illustration->illustrator;
        }
        $phrase_words       = explode(' ', $phrase->phrase_text);
        $shared_word_details= $this->sharedWords($phrase, $phrase_words);
        return View::make('user.user_plan.learning_center.detail_context')->with([
            'context'           => $context->context_name,
            'context_id'        => $context->context_id,
            'phrase'            => $phrase->phrase_text,
            'meaning'           => $meaning,
            'translations'      => $translations,
            'illustration'      => $illustration,
            'related_phrases'   => $related_phrases,
            'phrase_words'      => $phrase_words,
            'shared_words_arry' => $shared_word_details,
            'type'              => 'context_forwarded'
        ]);
    }

    /**
     * @param $phrase_id
     * @return mixed
     */
    public function phraseDetail2($phrase_id){
        $phrase             = $this->phrase_service->findById($phrase_id);
        $data = [
            'phrase_id'     => $phrase->phrase_id,
            'position'      => 1
        ];
        $meaning            = $this->meaning_service->meaning_data($data);
        $translations       = $this->contributor_service->getTranslations($data);
        $illustration       = $this->contributor_service->getIllustration($data);
        if($illustration != null){
            $illustration = $illustration->illustrator;
        }
        $related_phrases    = $this->context_phrase_service->getRelatedPhraseByPhraseId($phrase_id);
        $phrase_words       = explode(' ', $phrase->phrase_text);
        $shared_word_details= $this->sharedWords($phrase, $phrase_words);
        return View::make('user.user_plan.learning_center.detail_context')->with([
            'phrase'                => $phrase->phrase_text,
            'meaning'               => $meaning,
            'translations'          => $translations,
            'illustration'          => $illustration,
            'type'                  => 'phrase_forwarded',
            'selected_phrase_text'  => $phrase->phrase_text,
            'phrase_words'          => $phrase_words,
            'shared_words_arry'     => $shared_word_details,
            'related_phrases'       => $related_phrases
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search_context(Request $request){
        return $this->exploreContext($request->search);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search_word(Request $request){
        $phrases = $this->phrase_service->findAllLike($request->search);
        return View::make('user.user_plan.learning_center.explore_context2')->with(['phrases' => $phrases, 'phrases_searched' => $request->search, 'type' => 'phrase_forwarded' ]);
    }

    /**
     * @param $phrase
     * @param $phrase_words
     * @return array
     */
    public function sharedWords($phrase, $phrase_words){
        $shared_word_details= [];
        foreach ($phrase_words as $phrase_word){
            $shared_words_data  = [
                'long_phrase_id'    => $phrase->phrase_id,
                'shared_word'       => $phrase_word
            ];
            $shared_word_detail = $this->phrase_service->getSharedWords($shared_words_data);
            foreach ($shared_word_detail as $word_detail){
                $word_detail->sibling_name = $this->phrase_service->findById($word_detail->sibling_id)->phrase_text;
            }
            array_push($shared_word_details, $shared_word_detail);
        }
        return $shared_word_details;
    }
}