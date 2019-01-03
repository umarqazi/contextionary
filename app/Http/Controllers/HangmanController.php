<?php
/**
 * Created by PhpStorm.
 * User: Haris
 * Date: 8/15/18
 * Time: 12:07 PM
 */

namespace App\Http\Controllers;

use App\Context;
use App\ContextPhrase;
use App\Services\ContextPhraseService;
use App\Services\ContextService;
use Illuminate\Support\Facades\View;

class HangmanController extends Controller
{

    /**
     * @var ContextPhraseService
     */
    protected $context_phrase_service;

    /**
     * @var ContextService
     */
    protected $context_service;

    /**
     * HangmanController constructor.
     */
    public function __construct()
    {
        $context_phrase_service         = new ContextPhraseService();
        $context_service                = new ContextService();
        $this->context_phrase_service   = $context_phrase_service;
        $this->context_service          = $context_service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('user.user_plan.games.hangman_index');
    }

    /**
     * @return mixed
     */
    public function getPhrase(){
        $context            = $this->context_service->get();
        $context_phrases    = $this->context_phrase_service->getRandContextPhrase($context->context_id);
        if($context_phrases->count() > 0){
            return View::make('user.user_plan.games.hangman')->with(['context_phrases'=> $context_phrases, 'context' => $context->context_name, 'picture'=>$context->context_picture]);
        }else{
            return $this->getPhrase();
        }
    }
}