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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpWord\IOFactory;
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
        $context_length=0;
        return view::make('user.user_plan.reading_assistant.context_finder')->with(['flag'=>false,'length'=>$context_length]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pContextFinder(Request $request){
        $validator = Validator::make($request->all(), [
            'context' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('context-finder')
                ->withErrors($validator)
                ->withInput();
        }
        $data           =   [
                                'text'      =>  $request->context,
                                'user_id'   =>  Auth::user()->id,
                                'date'      =>  Carbon::now()
                            ];
        $this->read_assistant_service->saveHistory($data);
        $final_string   =   str_replace(' ', '_', $request->context);
        $client         =   new Client();
        $res            =   $client->get('http://52.43.120.48:5400/'.$final_string);
        $response       =   $res->getStatusCode(); // 200
        if( $response   ==  '200') {
            $body = json_decode($res->getBody());
            $context_list           = [];
            $string                 = [];
            foreach ($body as $context_div) {
                foreach ($context_div as $context_id => $context) {
                    $context_obj = $this->context_service->findById(intval($context_id))->toArray();
                    $final_string_array[$context_id] = explode("_", $final_string);
                    $string2 = [];
                    foreach ($context as $phrase_key => $phrase) {
                        $location = str_replace(array('{', '}'), '', $phrase->keyword_location[0]);
                        $locaion   =    explode(', ', $location);
                        $string2= array_merge($string2, $locaion);
                    }
                    $string2=array_unique($string2);
                    sort($string2);
                    foreach ($final_string_array[$context_id] as $key1 => $word) {
                        foreach ($string2 as $key2) {
                            if ($key1 == ($key2 -1)){
                                $final_string_array[$context_id][$key1] = '<span class="orange-clr">'.$word.'</span>';
                            }
                        }
                    }
//                            foreach ($final_string_array[$context_id] as $key => $word){
////                                if ((strtolower($word) == strtolower($phrase->keyword_text))) {
////                                    $final_string_array[$context_id][$key] = '<a href="#phrase-'.$phrase->keyword_phrase_id.'">'.$word.'</a>';
////                                }
//                            }
//                        $context_obj['phrases'][$phrase_key] = $this->getPhraseDetails($context_id, $phrase);
//                }
                    array_push($context_list, $context_obj);
                    $string[$context_id] = implode(" ",$final_string_array[$context_id]);
                }
            }
//            $export_data = $this->exportDataGenerator($context_list);
//            Session::put('export_data' , $export_data);
            $context_length=strlen($request->context);
            return View::make('user.user_plan.reading_assistant.context_finder')->with(['flag'=> true, 'string' => $string, 'context_list' => $context_list, 'length'=>$context_length]);
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
        $data=Session::get('export_data');
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=context_finder.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
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

    function exportDataGenerator($context_list){
        $data           = [];
        $data_array     = [];
        foreach($context_list as $context){
            if(isset($context['phrases'])){
                if($context['phrases'] != null){
                    foreach($context['phrases'] as $phrases) {
                        $data['context_name']   = $context['context_name'];
                        $data['jargon']         = $phrases['phrase'];
                        $data['meaning']        = $phrases['meaning'];
                        $related_phrase_array   = [];
                        foreach($phrases['related_phrase'] as $related_phrase) {
                            if(isset($related_phrase->details)){
                                if($related_phrase->details['phrase'] != ''){
                                    array_push($related_phrase_array, $related_phrase->details['phrase']);
                                }
                            }
                        }
                        $data['French_translation']  = '';
                        $data['Hindi_translation']  = '';
                        $data['Spanish_translation']  = '';
                        foreach($phrases['translation'] as $translation) {
                            if( isset($translation['translation']) && isset($translation['language']) ){
                                if($translation['language'] == 'French'){
                                    $data['French_translation']  = $translation['translation'];
                                }
                                elseif($translation['language'] == 'Hindi'){
                                    $data['Hindi_translation']  = $translation['translation'];
                                }
                                elseif($translation['language'] == 'Spanish'){
                                    $data['Spanish_translation']  = $translation['translation'];
                                }
                            }
                        }
                        if($data['French_translation']  == '' ){
                            $data['French_translation'] = '-';
                        }
                        if($data['Hindi_translation']  == '' ){
                            $data['Hindi_translation'] = '-';
                        }
                        if($data['Spanish_translation']  == '' ){
                            $data['Spanish_translation'] = '-';
                        }
                        $data['related_phrase']        = implode (", ", $related_phrase_array);
                        array_push($data_array,$data);
                    }
                }
            }
        }
        return $data_array;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function docxToText(Request $request)
    {
        $phpWord = IOFactory::createReader('Word2007')->load($request->file('file')->path());
        $string = '';
        foreach($phpWord->getSections() as $section) {
            foreach($section->getElements() as $element) {
                if(method_exists($element,'getText')) {
                    $string = $string.$element->getText();
                }
            }
        }
        return $string;
    }
}