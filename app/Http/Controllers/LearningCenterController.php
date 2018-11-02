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
    }

    /**
     * @return mixed
     */
    public function index(){
        return View::make('guest_pages.learning_center');
    }

    /**
     * @return mixed
     */
    public function exploreContext(){
        $contexts = $this->context_service->listing();
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
     * @return mixed
     */
    public function detailWord(){
        return View::make('user.user_plan.learning_center.detail_word');
    }

    /**
     * @param $context_id
     * @return mixed
     */
    public function exploreContextPhrase($context_id){
        $context = $this->context_service->findById($context_id);
        $phrases = $this->context_phrase_service->getContextPhrase($context_id);
        return View::make('user.user_plan.learning_center.explore_context2')->with(['phrases'=> $phrases, 'context' => $context->context_name, 'context_id' => $context->context_id ]);
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     */
    public function phraseDetail($context_id, $phrase_id){
        $context        = $this->context_service->findById($context_id);
        $phrase         = $this->phrase_service->findById($phrase_id);
        $meaning        = $this->meaning_service->meaning($context->context_id,$phrase->phrase_id);
        $data = [
            'context_id'    => $context->context_id,
            'phrase_id'     => $phrase->phrase_id,
            'position'      => 1
        ];
        $translations   = $this->contributor_service->getTranslations($data);
        return View::make('user.user_plan.learning_center.detail_context')->with(['context'=>$context->context_name, 'phrase'=>$phrase->phrase_text, 'meaning'=> $meaning, 'translations' => $translations]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search_context(Request $request){
        $contexts = $this->context_service->findAllLike($request->search);
        return View::make('user.user_plan.learning_center.explore_context')->with('contexts', $contexts);
    }
}