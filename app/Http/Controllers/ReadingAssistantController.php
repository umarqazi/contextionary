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
use App\Services\TextHistoryService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Response;
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
     * @var PhraseService
     */
    protected $text_history_service;

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
        $this->text_history_service     = new TextHistoryService();
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
            $context_list           = [];
            $string                 = [];
            foreach ($body as $context_div) {
                foreach ($context_div as $context_id => $context) {
                    $context_obj = $this->context_service->findById(intval($context_id))->toArray();
                    $final_string_array[$context_id]     = explode("_",$final_string);
                    foreach ($context as $phrase_key => $phrase) {
                        foreach ($final_string_array[$context_id] as $key => $word){
                            if ((strtolower($word) == strtolower($phrase->keyword_text))) {
                                $final_string_array[$context_id][$key] = '<a href="#phrase-'.$phrase->keyword_phrase_id.'">'.$word.'</a>';
                            }
                        }
                        $context_obj['phrases'][$phrase_key] = $this->getPhraseDetails($context_id, $phrase);
                    }
                    array_push($context_list, $context_obj);
                    $string[$context_id] = implode(" ",$final_string_array[$context_id]);
                }
            }
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
            $meaning = '-';
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

    /**
     * @return mixed
     */
    public function textHistory(){
        $user_id = Auth::user()->id;
        $text_histories = $this->text_history_service->listingForUser($user_id);
        return view::make('user.user_plan.reading_assistant.text_history')->with(['text_histories' => $text_histories]);
    }

    /**
     *
     */
    public function Export(){
        $data = array(
            array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25),
            array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18),
            array("firstname" => "James", "lastname" => "Brown", "age" => 31),
            array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7),
            array("firstname" => "Michael", "lastname" => "Davis", "age" => 43),
            array("firstname" => "Sarah", "lastname" => "Miller", "age" => 24),
            array("firstname" => "Patrick", "lastname" => "Miller", "age" => 27)
        );
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=galleries.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $list = $data;

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }

}