<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteMeaning;
use App\Services\VoteService;
use Illuminate\Http\Request;
use View;
use Auth;
use Redirect;

class VoteController extends Controller
{
    protected $voteService;
    protected $contributor;
    protected $pageMenu;

    public function __construct()
    {
        $service=new VoteService();
        $this->voteService=$service;
        $contributorController=new ContributorController();
        $this->contributor=$contributorController;
    }
    /**
     * get all votes
     */
    public function phraseList(){
        $this->pageMenu=$this->contributor->voteMenus();
        $meanings=$this->voteService->getVoteList();
        return view::make('user.contributor.votes.phrase_list')->with(['contextList'=>$meanings,'pageMenu'=>$this->pageMenu]);
    }
    public function voteMeaning(Request $request){
        $data=['context_id'=>$request->context_id, 'phrase_id'=>$request->phrase_id];
        $this->pageMenu=$this->contributor->voteMenus();
        $meanings=$this->voteService->getVoteMeaning($data);
        if($meanings==false){
            $notification = array(
                'message' => 'You already cast your vote against this phrase',
                'alert_type' => 'danger',
            );
            $url=lang_url('plist');
            return Redirect::to($url)->with($notification);
        }
        return view::make('user.contributor.vote_meaning')->with(['phraseMeaning'=>$meanings,'pageMenu'=>$this->pageMenu]);
    }
    /**
     * add vote against meaning
     */
    public function vote(VoteMeaning $voteMeaning){
        $voteMeaning->validate();
        $data=['context_id'=>$voteMeaning['context_id'],'phrase_id'=>$voteMeaning['phrase_id'],'user_id'=>Auth::user()->id,'define_meaning_id'=>$voteMeaning['meaning'], 'grammer'=>$voteMeaning['meaning'],'spelling'=>$voteMeaning['meaning'],'audience'=>$voteMeaning['meaning'],'part_of_speech'=>$voteMeaning['meaning'],'vote'=>1];
        $addVote=$this->voteService->vote($data);
        if($addVote==true){
            $notification = array(
                'message' => 'Vote has been added Successfully',
                'alert_type' => 'success',
            );
            $redirect=lang_url('plist');
        }
        return Redirect::to($redirect)->with($notification);
    }
}
