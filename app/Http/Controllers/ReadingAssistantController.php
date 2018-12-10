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
use App\Services\ReadingAssistantService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;
use Auth;
use GuzzleHttp\Client;

class ReadingAssistantController extends Controller
{

    /**
     * @var ReadingAssistantService
     */
    protected $read_assistant_service;

    /**
     * @var MeaningService
     */
    protected $meaning_service;

    /**
     * @var ContextService
     */
    protected $contributor_service;

    /**
     * @var ContextPhraseService
     */
    protected $context_phrase_service;

    /**
     * @var ContextService
     */
    protected $context_service;

    /**
     * @var PhraseService
     */
    protected $phrase_service;

    /**
     * ReadingAssistantController constructor.
     */
    public function __construct()
    {
        $this->read_assistant_service   = new ReadingAssistantService();
        $this->meaning_service          = new MeaningService();
        $this->contributor_service      = new ContributorService();
        $this->context_phrase_service   = new ContextPhraseService();
        $this->context_service          = new ContextService();
        $this->phrase_service           = new PhraseService();
    }

    /**
     * @return mixed
     */
    public function contextFinder(){
        $context='';
        return view::make('user.user_plan.reading_assistant.context_finder')->with('flag', false);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pContextFinder(Request $request){
        $data           =   [
                                'text'=>$request->context,
                                'user_id'=>Auth::user()->id,
                                'date'=>Carbon::now()
                            ];
        $history        =   $this->read_assistant_service->saveHistory($data);
        $final_string   =   str_replace(' ', '_', $request->context);
        $client         =   new Client();
        $res            =   $client->get('http://54.189.114.107:5400/'.$final_string);
        $response       =   $res->getStatusCode(); // 200
        if( $response   ==  '200') {
            $body = json_decode($res->getBody());
            $final_string_array     = explode("_",$final_string);
            $context_list           = [];
            foreach ($body as $context_div) {
                foreach ($context_div as $context_id => $context) {
                    $context_obj = $this->context_service->findById(intval($context_id))->toArray();
                    foreach ($context as $phrase_key => $phrase) {
                        foreach ($final_string_array as $key => $word){
                            if ((strtolower($word) == strtolower($phrase->keyword_text))) {
                                $final_string_array[$key] = '<a href="#phrase-'.$phrase->keyword_phrase_id.'">'.$word.'</a>';
                            }
                        }
                        $context_obj['phrases'][$phrase_key] = $this->getPhraseDetails($context_id, $phrase);
                    }
                    array_push($context_list, $context_obj);
                }
            }
            $string = implode(" ",$final_string_array);
            return view::make('user.user_plan.reading_assistant.context_finder')->with(['flag'=> true, 'string' => $string, 'context_list' => $context_list]);
        }
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return array
     */
    public function contextGlossary($context_id, $phrase_id){
        $phrase = $this->getPhrase($phrase_id);
        return $this->getPhraseDetails($context_id, $phrase);
    }

    /**
     * @param $phrase_id
     * @return mixed
     */
    public function getPhrase($phrase_id){
         return $this->phrase_service->findById($phrase_id);
    }

    /**
     * @param $context_id
     * @param $phrase
     * @return array
     */
    public function getPhraseDetails($context_id, $phrase){
        $data = [
            'context_id'    => $context_id,
            'phrase_id'     => $phrase->keyword_phrase_id,
            'position'      => 1,
        ];
        $meaning        = $this->getMeaning($data);
        $translation    = $this->getTranslation($data);
        $illustration   = $this->getIllustration($data);
        $related_phrases= $this->context_phrase_service->getRelatedPhrase($context_id, $phrase->keyword_phrase_id);
        foreach($related_phrases as $key => $related_phrase){
            if($related_phrase->relatedPhrases != null){
                if($context_id == $related_phrase->context_id){
                    $related_phrase_phrase          = $this->getPhrase($related_phrase->related_phrase_id);
                    $related_phrase_phrase_details  = $this->getRelatedPhraseDetails($related_phrase->context_id, $related_phrase_phrase);
                    $related_phrases[$key]->details = $related_phrase_phrase_details;
                }
            }
        }
        $phrase_data = [
            'phrase'                    => ucfirst($phrase->keyword_text),
            'phrase_id'                 => $phrase->keyword_phrase_id,
            'meaning'                   => $meaning,
            'translation'               => $translation,
            'illustration'              => $illustration,
            'related_phrase'            => $related_phrases,
        ];
        return $phrase_data;
    }

    /**
     * @param $context_id
     * @param $phrase
     * @return array
     */
    public function getRelatedPhraseDetails($context_id, $phrase){
        $data = [
            'context_id'    => $context_id,
            'phrase_id'     => $phrase->phrase_id,
            'position'      => 1,
        ];
        $meaning        = $this->getMeaning($data);
        $translation    = $this->getTranslation($data);
        $illustration   = $this->getIllustration($data);
        $related_phrases= $this->context_phrase_service->getRelatedPhrase($context_id, $phrase->phrase_id);
        $phrase_data = [
            'phrase'        => ucfirst($phrase->phrase_text),
            'phrase_id'     => $phrase->phrase_id,
            'meaning'       => $meaning,
            'translation'   => $translation,
            'illustration'  => $illustration,
            'related_phrase'=> $related_phrases,
        ];
        return $phrase_data;
    }

    /**
     * @param $data
     * @return string
     */
    public function getMeaning($data){
        $meaning_data = $this->meaning_service->meaning_data($data);
        if(count($meaning_data) > 0){
            $meaning = $meaning_data[0]->meaning;
        }else{
            $meaning = 'No Meaning in Records';
        }
        return $meaning;
    }

    /**
     * @param $data
     * @return string
     */
    public function getIllustration($data){
        $illustration = $this->contributor_service->getIllustrations($data);
        if(count($illustration) > 0) {
            $illustration = $illustration->illustrator;
        }else{
            $illustration = '';
        }
        return $illustration;
    }

    /**
     * @param $data
     * @return array
     */
    public function getTranslation($data){
        $translation_data = $this->contributor_service->getTranslations($data);
        if(count($translation_data) > 0){
            $translation = $translation_data->toArray();
        }else{
            $translation = [];
        }
        return $translation;
    }

}