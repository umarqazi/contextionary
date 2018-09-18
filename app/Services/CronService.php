<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/14/18
 * Time: 9:11 PM
 */

namespace App\Services;


use App\Repositories\BiddingExpiryRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\IllustratorRepo;
use Carbon\Carbon;
use Config;

class CronService
{
    protected $defineMeaning;
    protected $biddingRepo;
    protected $voteService;
    protected $illustrator;

    public function __construct()
    {
        $biddingRepo=new BiddingExpiryRepo();
        $defineMeaning=new DefineMeaningRepo();
        $voteService=new VoteService();
        $illustrator=new IllustratorRepo();
        $this->biddingRepo=$biddingRepo;
        $this->defineMeaning=$defineMeaning;
        $this->voteService=$voteService;
        $this->illustrator=$illustrator;
    }

    /*
     * cron job for checking availability of meaning for vote
     */
    public function checkMeaning(){
        $this->bidExpiry(env('MEANING'), 'defineMeaning');
    }
    /**
     * update illustrator
     */
    public function checkIllustratorBid(){
        $this->bidExpiry(env('ILLUSTRATE'), 'illustrator');
    }
    /**
     * function for updating bids and add record for votes
     */
    public function bidExpiry($type, $model){
        $today=Carbon::today();
        $getAllMeaning=$this->biddingRepo->fetchBidding($type);
        if($getAllMeaning){
            foreach($getAllMeaning as $meaning):
                $context_id=$meaning['context_id'];
                $phrase_id=$meaning['phrase_id'];
                $expiry_date=$meaning['expiry_date'];
                if($context_id!=NULL && $phrase_id!=NULL):
                    $cron_job='0';
                    $checkTotal=$this->$model->totalMeaning($context_id, $phrase_id);
                    if($checkTotal < Config::get('constant.total_bids')):
                        if(Carbon::parse($expiry_date) < Carbon::parse($today)):
                            $cron_job='1';
                        endif;
                    else:
                        $cron_job='1';
                    endif;
                    if($cron_job=='1'):
                        $updateMeaningStatus=$this->$model->updateMeaningStatus($context_id, $phrase_id);
                        $this->voteService->addPhraseForVote($context_id, $phrase_id, $type);
                        $updateBidding=$this->biddingRepo->updateBiddingStatus($meaning['id']);
                    endif;
                endif;
            endforeach;
        }
    }
}