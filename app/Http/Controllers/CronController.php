<?php

namespace App\Http\Controllers;

use App\Services\CronService;
use App\Services\VoteService;
use Illuminate\Http\Request;

class CronController extends Controller
{
    protected $cronService;
    protected $voteService;

    /**
     * CronController constructor.
     */
    public function __construct()
    {
        $contributorService=new CronService();
        $this->cronService=$contributorService;
        $voteService=new VoteService();
        $this->voteService=$voteService;
    }

    public function meaningToVote(){
        $this->cronService->checkMeaning();
    }
    /**
     * check expired votes
     */
    public function checkExpiredVotes(){
        $this->cronService->checkDefineExpiredVotes();
    }
    /**
     * check illustrator expiry
     */
    public function illustratorBidtoVote(){
        $this->cronService->checkIllustratorBid();
    }
    /**
     *
     */
    public function checkIllustratorVotes(){
        $this->cronService->illustratorVotes();
    }

    public function translateBidtoVote(){
        $this->cronService->checkTranslateBid();
    }

    public function translateVote(){
        $this->cronService->translationVotes();
    }
}
