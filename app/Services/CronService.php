<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/14/18
 * Time: 9:11 PM
 */

namespace App\Services;


use App\Http\Controllers\SettingController;
use App\Repositories\BiddingExpiryRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\IllustratorRepo;
use App\Repositories\UserPointRepo;
use App\Repositories\VoteExpiryRepo;
use App\Repositories\VoteMeaningRepo;
use Carbon\Carbon;
use Config;
use Mail;
use App\Mail\Meanings;

class CronService
{
    protected $defineMeaning;
    protected $biddingRepo;
    protected $voteService;
    protected $illustrator;
    protected $minimum_bids;
    protected $bids_expiry;
    protected $vote_expiry;
    protected $voteExpiryRepo;
    protected $voteMeaningRepo;
    protected $userPoint;

    public function __construct()
    {
        $biddingRepo=new BiddingExpiryRepo();
        $defineMeaning=new DefineMeaningRepo();
        $voteService=new VoteService();
        $illustrator=new IllustratorRepo();
        $expiryRepo=new VoteExpiryRepo();
        $this->voteMeaningRepo=new VoteMeaningRepo();
        $this->userPoint=new UserPointRepo();
        $this->biddingRepo=$biddingRepo;
        $this->defineMeaning=$defineMeaning;
        $this->voteService=$voteService;
        $this->illustrator=$illustrator;
        $this->voteExpiryRepo=$expiryRepo;
        $setting = new SettingController();
        $this->minimum_bids=$setting->getKeyValue(env('MINIMUM_BIDS'))->values;
        $this->bids_expiry=$setting->getKeyValue(env('BIDS_EXPIRY'))->values;
        $this->vote_expiry=$setting->getKeyValue(env('VOTE_EXPIRY'))->values;
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
                    if(Carbon::parse($expiry_date) < Carbon::parse($today)):
                        if($checkTotal >= $this->minimum_bids):
                            $cron_job='1';
                        else:
                            $date=Carbon::now()->addMinutes($this->bids_expiry);
                            $expiry_update=['expiry_date'=>$date];
                            $this->biddingRepo->updateBiddingStatus($meaning['id'], $expiry_update);
                        endif;
                    endif;
                    if($cron_job=='1'):
                        $updateMeaningStatus=$this->$model->updateMeaningStatus($context_id, $phrase_id);
                        $this->voteService->addPhraseForVote($context_id, $phrase_id, $type);
                        $record_update=['status'=>'1'];
                        $updateBidding=$this->biddingRepo->updateBiddingStatus($meaning['id'], $record_update);
                    endif;
                endif;
            endforeach;
        }
    }
    /**
     * check expired votes
     */
    public function checkExpiredVotes(){
        $getVote=$this->voteExpiryRepo->getAllMeaningVotes(env("MEANING"));
        if($getVote):
            foreach($getVote as $vote):
                $cron_run='0';
                $getTotalVote=$this->voteMeaningRepo->totalVotes($vote['context_id'], $vote['phrase_id']);
                if($vote['expiry_date'] < Carbon::today()):
                    if($getTotalVote >= env('MINIMUM_VOTES')):
                        $cron_run='1';
                    else:
                        echo $date=Carbon::now()->addMinutes($this->vote_expiry);
                        die();
                        $expiry_update=['expiry_date'=>$date];
                        $this->voteExpiryRepo->updateStatus($vote['id'], $expiry_update);
                    endif;
                endif;
                if($cron_run=='1'):
                    $getHighestVotes=$this->voteMeaningRepo->hightVotes($vote['context_id'], $vote['phrase_id']);
                    if(!empty($getHighestVotes)):
                        $this->defineMeaning->updateVoteStatus($vote['context_id'], $vote['phrase_id']);
                        foreach($getHighestVotes as $key=>$hightesVotes):
                            if($key+1=='1'):
                                $points=10;
                            else:
                                $points=1;
                            endif;
                            $data=['point'=>$points,'context_id'=>$vote['context_id'], 'phrase_id'=>$vote['phrase_id'], 'user_id'=>$hightesVotes['meaning']['user_id'], 'position'=>$key+1];
                            $this->userPoint->create($data);
                            $hightesVotes['points']=$points;
                            if($key+1==1):
                                $position='1st';
                            elseif($key+1==2):
                                $position='2nd';
                            else:
                                $position='3rd';
                            endif;
                            $hightesVotes['position']=$position;
                            Mail::to($hightesVotes['meaning']['users']['email'])->send(new Meanings($hightesVotes));
                            $this->defineMeaning->update(['position'=>$key+1], $hightesVotes['meaning']['id']);
                        endforeach;
                        $records=['status'=>'1'];
                        $this->voteExpiryRepo->updateStatus($vote['id'], $records);
                    endif;
                endif;
            endforeach;
        endif;
    }
}