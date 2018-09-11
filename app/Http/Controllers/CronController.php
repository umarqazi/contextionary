<?php

namespace App\Http\Controllers;

use App\Services\ContributorService;
use App\Services\VoteService;
use Illuminate\Http\Request;

class CronController extends Controller
{
    protected $contributorService;
    protected $voteService;

    public function __construct()
    {
        $contributorService=new ContributorService();
        $this->contributorService=$contributorService;
        $voteService=new VoteService();
        $this->voteService=$voteService;
    }
    public function meaningToVote(){
        $this->contributorService->checkMeaning();
    }
    /**
     * check expired votes
     */
    public function checkExpiredVotes(){
        $this->voteService->checkExpiredVotes();
    }
}
