<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteMeaning;
use App\Services\VoteService;
use Illuminate\Http\Request;
use View;
use Auth;
use Redirect;
use Validator;

class VoteController extends Controller
{
    protected $voteService;
    protected $contributor;

    /**
     * VoteController constructor.
     */
    public function __construct()
    {
        $service=new VoteService();
        $this->voteService=$service;
        $contributorController=new ContributorController();
        $this->contributor=$contributorController;
    }
    /**
     * @return mixed
     * get all votes
     */
    public function phraseList(){
        $meanings=$this->voteService->getVoteList();
        $data=['route'=>'voteMeaning', 'title'=>'Phrase for Illustrator'];
        return view::make('user.contributor.votes.phrase_list')->with(['data'=>$data,'contextList'=>$meanings]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function voteMeaning(Request $request){
        $data=['context_id'=>$request->context_id, 'phrase_id'=>$request->phrase_id];
        $meanings=$this->voteService->getVoteMeaning($data);
        if(array_key_exists('voteStatus', $meanings)){
            $notification = array(
                'message' => $meanings['message'],
                'alert_type' => 'error',
            );
            $url=lang_url('phrase-list');
            return Redirect::to($url)->with($notification);
        }
        return view::make('user.contributor.votes.vote_meaning')->with(['phraseMeaning'=>$meanings]);
    }
    /**
     * @param VoteMeaning $voteMeaning
     * @return mixed
     * add vote against meaning
     */
    public function vote(VoteMeaning $voteMeaning){
        $voteMeaning->validate();
        $data=['context_id'=>$voteMeaning['context_id'],'phrase_id'=>$voteMeaning['phrase_id'],'user_id'=>Auth::user()->id,'define_meaning_id'=>$voteMeaning['meaning'], 'vote'=>1, 'type'=>env('MEANING')];
        $addVote=$this->voteService->vote($data);
        if($addVote['status']==false){
            $notification = array(
                'message' => $addVote['message'],
                'alert_type' => 'error',
            );
            return Redirect::back()->with($notification)->withInput(Input::all());
        }else{
            $notification = array(
                'message' => trans('content.vote_successfully_done'),
                'alert_type' => 'success',
            );
            $redirect=lang_url('phrase-list');
        }
        return Redirect::to($redirect)->with($notification);
    }
    /**
     * @param Request $request
     * @return mixed
     * mark all meaning as poor quality
     */
    public function poorQuality(Request $request){
        $data=['context_id'=>$request->context_id,'phrase_id'=>$request->phrase_id, 'type'=>$request->type];
        if($request->type==env('ILLUSTRATE')){
            $url=lang_url('illustrator-vote-list');
        }elseif($request->type==env('MEANING')){
            $url=lang_url('phrase-list');
        }else{
            $data['language']=Auth::user()->profile->language_proficiency;
            $url=lang_url('translate-vote-list');
        }
        $vote=$this->voteService->poorQualityVote($data);
        if(array_key_exists('voteStatus', $vote)){
            $notification = array(
                'message' => $vote['message'],
                'alert_type' => 'error',
            );
        }else{
            $notification = array(
                'message' => trans('content.vote_successfully_done'),
                'alert_type' => 'success',
            );
        }
        return Redirect::to($url)->with($notification);
    }

    /**
     * @return mixed
     * illustrator list for voting
     */
    public function voteIllustrator(){
        $meanings=$this->voteService->getIllustratorVoteList();
        $data=['route'=>'voteIllustrator', 'title'=>'Phrase for Illustrator'];
        return view::make('user.contributor.votes.phrase_list')->with(['data'=>$data,'contextList'=>$meanings]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getSelectedIllustrators(Request $request){
        $data=['context_id'=>$request->context_id, 'phrase_id'=>$request->phrase_id, 'user_id'=>Auth::user()->id];
        $illustrators=$this->voteService->getVoteIllustrator($data);
        if(array_key_exists('voteStatus', $illustrators)){
            $notification = array(
                'message' => $illustrators['message'],
                'alert_type' => 'error',
            );
            $url=lang_url('illustrator-vote-list');
            return Redirect::to($url)->with($notification);
        }
        return view::make('user.contributor.votes.vote_illustrator')->with(['illustrators'=>$illustrators]);
    }
    /**
     * @param Request $request
     * @return mixed
     * add vote against illustrator
     */
    public function saveVoteIllustrator(Request $request){
        $validators = Validator::make($request->all(), [
            'ambiguous' => 'required',
            'negative_contation' => 'required',
            'text_image' => 'required',
            'illustrator'=>'required'
        ]);
        if ($validators->fails()) {
            return redirect::back()
                ->withErrors($validators)
                ->withInput();
        }
        $data=['context_id'=>$request['context_id'],'phrase_id'=>$request['phrase_id'],'user_id'=>Auth::user()->id,'illustrator_id'=>$request['illustrator'],'vote'=>1, 'type'=>env('ILLUSTRATE')];
        $addVote=$this->voteService->vote($data);
        $notification = array(
            'message' => trans('content.vote_successfully_done'),
            'alert_type' => 'success',
        );
        $redirect=lang_url('illustrator-vote-list');
        return Redirect::to($redirect)->with($notification);
    }

    public function voteTranslator(){
        $translations=$this->voteService->getTranslatorVoteList();
        $data=['route'=>'voteTranslator', 'title'=>'Phrase for Translator'];
        return view::make('user.contributor.votes.phrase_list')->with(['data'=>$data,'contextList'=>$translations]);
    }

    public function getSelectedTranslations($context_id, $phrase_id){
        $data=['context_id'=>$context_id, 'phrase_id'=>$phrase_id, 'user_id'=>Auth::user()->id, 'language'=>Auth::user()->profile->language_proficiency];
        $translations=$this->voteService->getVoteTranslators($data);
        if(array_key_exists('voteStatus', $translations)){
            $notification = array(
                'message' => $translations['message'],
                'alert_type' => 'error',
            );
            $url=lang_url('translate-vote-list');
            return Redirect::to($url)->with($notification);
        }
        return view::make('user.contributor.votes.vote_translation')->with(['phraseMeaning'=>$translations]);
    }

    public function translationVote(VoteMeaning $voteMeaning){
        $voteMeaning->validate();
        $data=['context_id'=>$voteMeaning['context_id'],'phrase_id'=>$voteMeaning['phrase_id'],'user_id'=>Auth::user()->id,'translation_id'=>$voteMeaning['meaning'], 'vote'=>1, 'type'=>env('TRANSLATE'), 'language'=>Auth::user()->profile->language_proficiency];
        $addVote=$this->voteService->vote($data);
        if($addVote['status']==false){
            $notification = array(
                'message' => $addVote['message'],
                'alert_type' => 'error',
            );
            return Redirect::back()->with($notification)->withInput(Input::all());
        }else{
            $notification = array(
                'message' => trans('content.vote_successfully_done'),
                'alert_type' => 'success',
            );
            $redirect=lang_url('translate-vote-list');
        }
        return Redirect::to($redirect)->with($notification);
    }
}
