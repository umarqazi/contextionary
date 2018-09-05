<?php

namespace App\Http\Controllers;

use App\Services\ContributorService;
use Illuminate\Http\Request;

class CronController extends Controller
{
    protected $contributorService;
    public function __construct()
    {
        $contributorService=new ContributorService();
        $this->contributorService=$contributorService;
    }
    public function meaningToVote(){
        $this->contributorService->checkMeaning();
    }
}
