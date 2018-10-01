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
        $data=['context_id'=>$voteMeaning['context_id'],'phrase_id'=>$voteMeaning['phrase_id'],'user_id'=>Auth::user()->id,'define_meaning_id'=>$voteMeaning['meaning'], 'grammer'=>$voteMeaning['grammer'],'spelling'=>$voteMeaning['spelling'],'audience'=>$voteMeaning['audience'],'part_of_speech'=>$voteMeaning['part_of_speech'],'vote'=>1, 'type'=>env('MEANING')];
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
        if($request->type=='ILLUSTRATE'){
            $key=env('ILLUSTRATOR_KEY');
            $url=lang_url('illustrator-vote-list');
        }else{
            $key=env('MEANING_KEY');
            $url=lang_url('phrase-list');
        }
        $data=['context_id'=>$request->context_id,'phrase_id'=>$request->phrase_id, 'type'=>env($request->type), 'key'=>$key];
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
            'ambagious' => 'required',
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
}
